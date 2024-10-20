<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Bank;
use App\Models\Marche;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $marches = Marche::all();
        $suppliers = Supplier::all();
        $banks = Bank::all();
        $marche = $request->marche;
        $supplier = $request->supplier;
        $bank = $request->bank;
        return Inertia::render('Purchase/Index',compact('marches','suppliers','banks','bank','supplier','marche'));
    }

    public function fetchpurchases(Request $request)
    {
        $total = Purchase::count();
        $query = Purchase::query()->with('bank','marche','supplier','payment');
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%");
            });
        }
        $itemsPerPage = $request->input('itemsPerPage');
        $query->orderBy('id', 'DESC');
        $marche = $request->marche;
        if(isset($marche)){
            $query->where('marche_id',$marche);
        }
        $supplier = $request->supplier;
        if(isset($supplier)){
            $query->where('supplier_id',$supplier);
        }
        $bank = $request->bank;
        if(isset($bank)){
            $query->where('bank_id',$bank);
        }
        $date_debut = $request->date_debut;
        $date_fin = $request->date_fin;

        if (!is_null($date_debut) || !is_null($date_fin)) {
            $query->where(function ($q) use ($date_debut, $date_fin) {
                if (!is_null($date_debut) && !is_null($date_fin)) {
                    $q->whereBetween('date', [$date_debut, $date_fin]);
                } elseif (!is_null($date_debut)) {
                    $q->where('date', '>=', $date_debut);
                } elseif (!is_null($date_fin)) {
                    $q->where('date', '<=', $date_fin);
                }
            });
        }
        $allitems = $query->get();
        $totalht = 0;
        $totaltva = 0;
        $totalttc = 0;
        foreach($allitems as $item){
            $totalht += $item->total;
            $totaltva += round((float)$item->total * $item->payment->taux_tva*0.01,2);
            $totalttc += round((float)$item->total + (float)$item->total * $item->payment->taux_tva*0.01,2);
        }
        if($itemsPerPage >= 1){
            $items = $query->paginate($request->input('itemsPerPage'));
        }
        if(!$itemsPerPage){
            $items = $query->paginate(10);
        }
        if($itemsPerPage == -1){
            $items = $query->paginate($total);
        }

        return response()->json([
            'purchases' => $items,
            'total' => $items->total(),
            'totalht' => round($totalht,2),
            'totaltva' => round($totaltva,2),
            'totalttc' => round($totalttc,2)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $banks = Bank::all();
        $suppliers = Supplier::all();
        $marches = Marche::all();
        return Inertia::render('Purchase/Create',compact('banks','suppliers','marches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        $data = $request->validated();
        $year = Carbon::now()->format('Y');
        $countpurchases = 2;
        $purchase = Purchase::create([
            'numero' => 1,
            'bank_id' => $data['bank_id'],
            'supplier_id' => $data['supplier_id'],
            'date' => Carbon::parse($data['date'])->format('Y-m-d'),
            'marche_id' => $data['marche_id'],
            'total' => $data['total'],
        ]);
        foreach($data['products'] as $product){
            $product = Product::Create([
                'purchase_id' => "1",
                'designation' => $product['designation'],
                'quantity' => $product['quantity'],
                'unity' => "1",
                'priceperunity' => $product['priceperunity'],
                'subtotal' => round($product['quantity']*$product['priceperunity'],2),
            ]);
        }
        $countpayments = Payment::whereBetween('created_at', [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear(),
        ])->count();
        $payment = Payment::create([
            'numero' => str_pad($countpayments+1, 4, '0', STR_PAD_LEFT) .'/'. $year,
            'purchase_id' => $purchase->id,
            'echeance_date' => $data['echeance_date'],
            'taux_tva' => $data['taux_tva'],
            'paiement_immediat' => $data['paiement_immediat'],
            'type' => $data['type'],
            'motif_virement' => $data['motif'],
            'status' => 'pending',
        ]);
        if($request->hasFile('proof_file') && $data['type'] != 'Virement'){
            $proof_file = $request->file('proof_file');
            $path = $proof_file->store('/uploads', 'local');
            $payment->proof_file = $proof_file->hashName();
            $payment->original_file_name = $proof_file->getClientOriginalName();
            $payment->save();
        }
        if($data['type'] == 'Virement'){
            $payment->has_been_virement = true;
            $countvirements = Payment::whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])->where('has_been_virement',true)->withTrashed()->count();
            $payment->numero_virement = str_pad($countvirements+1, 4, '0', STR_PAD_LEFT) .'/'. $year;
            $payment->motif_virement = $data['motif'];
            $payment->save();
            $datapdf = [
                'numero_virement' => $payment->numero_virement,
                'bank' => $purchase->bank->name,
                'rib' => substr($purchase->bank->rib, 0, 3).' '.substr($purchase->bank->rib, 3, 3).' '.substr($purchase->bank->rib, 6, 16).' '.substr($purchase->bank->rib, 22, 2),
                'montant' => round($purchase->total+$purchase->total*$purchase->payment->taux_tva * 0.01,2),
                'supplier_name' => $purchase->supplier->name,
                'supplier_rib' => substr($purchase->supplier->bank_rib, 0, 3).' '.substr($purchase->supplier->bank_rib, 3, 3).' '.substr($purchase->supplier->bank_rib, 6, 16).' '.substr($purchase->supplier->bank_rib, 22, 2),
                'supplier_bank' => $purchase->supplier->bank_name,
                'motif' => $data['motif'],
                'tenant' => tenant('id'),
                'date' => Carbon::parse($data['date'])->format('d/m/Y')
            ];
            $pdf = Pdf::loadView('pdf.virement',$datapdf);
            $hashName = Str::random(40).'.pdf';
            $directory = 'uploads';
            // Check if the directory exists, and create it if not
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $filePath = $pdf->save(storage_path('app/uploads/'.$hashName));
            $payment->proof_file = $hashName;
            $payment->original_file_name = 'Virement_'.preg_replace('/\//', '-', $payment->numero_virement, 1).'.pdf';
            $payment->save();
        }
        
        return Redirect::route('purchases.index')->with([
            'success' => 'Achat sauvegardé avec succès.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase = Purchase::with('bank', 'marche', 'supplier', 'payment', 'products')->find($id);
        return Inertia::render('Purchase/Show',compact('purchase'));
    }

    public function download(string $id){
        $payment = Payment::find($id);
        $path = Storage::path('uploads/'. $payment->proof_file);
        return response()->download($path, $payment->original_file_name);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $banks = Bank::all();
        $suppliers = Supplier::all();
        $marches = Marche::all();
        $purchase = Purchase::with('bank', 'marche', 'supplier', 'payment', 'products')->find($id);
        return Inertia::render('Purchase/Edit',compact('purchase','banks','suppliers','marches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseRequest $request, string $id)
    {
        $data = $request->validated();
        $year = Carbon::now()->format('Y');
        $purchase = Purchase::with('bank', 'marche', 'supplier', 'payment', 'products')->find($id);
        $purchase->update([
            'bank_id' => $data['bank_id'],
            'supplier_id' => $data['supplier_id'],
            'marche_id' => $data['marche_id'],
            'date' => Carbon::parse($data['date'])->format('Y-m-d'),
            'total' => $data['total'],
        ]);
        Product::where('purchase_id',$id)->delete();
        foreach($data['products'] as $product){
            $product = Product::Create([
                'purchase_id' => $purchase->id,
                'designation' => $product['designation'],
                'quantity' => $product['quantity'],
                'unity' => $product['unity'],
                'priceperunity' => $product['priceperunity'],
                'subtotal' => round($product['quantity']*$product['priceperunity'],2),
            ]);
        }
        $payment = Payment::where('purchase_id',$id)->first();
        
        $payment->update([
            'echeance_date' => $data['echeance_date'],
            'taux_tva' => $data['taux_tva'],
            'paiement_immediat' => $data['paiement_immediat'],
            'type' => $data['type'],
            'status' => 'pending',
            'motif_virement' => $data['motif'],
        ]);
        
        if($request->hasFile('proof_file') && $data['type'] != 'Virement'){
            //delete old file
            if($payment->proof_file){
                Storage::disk('local')->delete('/uploads/'.$payment->proof_file);
            }
            $proof_file = $request->file('proof_file');
            $path = $proof_file->store('/uploads', 'local');
            $payment->proof_file = $proof_file->hashName();
            $payment->original_file_name = $proof_file->getClientOriginalName();
            $payment->save();
        }
        $purchase = Purchase::find($id);
        if($data['type'] == 'Virement'){
            if($payment->has_been_virement){
                Storage::disk('local')->delete('/uploads/'.$payment->proof_file);
                $datapdf = [
                    'numero_virement' => $payment->numero_virement,
                    'bank' => $purchase->bank->name,
                    'rib' => substr($purchase->bank->rib, 0, 3).' '.substr($purchase->bank->rib, 3, 3).' '.substr($purchase->bank->rib, 6, 16).' '.substr($purchase->bank->rib, 22, 2),
                    'montant' => round($purchase->total + ($purchase->total * $purchase->payment->taux_tva * 0.01),2),
                    'supplier_name' => $purchase->supplier->name,
                    'supplier_rib' => substr($purchase->supplier->bank_rib, 0, 3).' '.substr($purchase->supplier->bank_rib, 3, 3).' '.substr($purchase->supplier->bank_rib, 6, 16).' '.substr($purchase->supplier->bank_rib, 22, 2),
                    'supplier_bank' => $purchase->supplier->bank_name,
                    'motif' => $data['motif'],
                    'tenant' => tenant('id'),
                    'date' => Carbon::parse($data['date'])->format('d/m/Y')
                ];
                $pdf = Pdf::loadView('pdf.virement',$datapdf);
                $hashName = Str::random(40).'.pdf';
                $filePath = $pdf->save(storage_path('app/uploads/'.$hashName));
                $payment->proof_file = $hashName;
                $payment->original_file_name = 'Virement_'.preg_replace('/\//', '-', $payment->numero_virement, 1).'.pdf';
                $payment->save();
            }else{
                $payment->has_been_virement = true;
                $countvirements = Payment::whereBetween('created_at', [
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear(),
                ])->where('has_been_virement',true)->withTrashed()->count();
                $payment->numero_virement = str_pad($countvirements+1, 4, '0', STR_PAD_LEFT) .'/'. $year;
                $payment->save();
                Storage::disk('local')->delete('/uploads/'.$payment->proof_file);
                $datapdf = [
                    'numero_virement' => $payment->numero_virement,
                    'bank' => $purchase->bank->name,
                    'rib' => substr($purchase->bank->rib, 0, 3).' '.substr($purchase->bank->rib, 3, 3).' '.substr($purchase->bank->rib, 6, 16).' '.substr($purchase->bank->rib, 22, 2),
                    'montant' => round($purchase->total + ($purchase->total * $purchase->payment->taux_tva * 0.01),2),
                    'supplier_name' => $purchase->supplier->name,
                    'supplier_rib' => substr($purchase->supplier->bank_rib, 0, 3).' '.substr($purchase->supplier->bank_rib, 3, 3).' '.substr($purchase->supplier->bank_rib, 6, 16).' '.substr($purchase->supplier->bank_rib, 22, 2),
                    'supplier_bank' => $purchase->supplier->bank_name,
                    'motif' => $data['motif'],
                    'tenant' => tenant('id'),
                    'date' => Carbon::parse($data['date'])->format('d/m/Y')
                ];
                $pdf = Pdf::loadView('pdf.virement',$datapdf);
                $hashName = Str::random(40).'.pdf';
                $filePath = $pdf->save(storage_path('app/uploads/'.$hashName));
                $payment->proof_file = $hashName;
                $payment->original_file_name = 'Virement_'.preg_replace('/\//', '-', $payment->numero_virement, 1).'.pdf';
                $payment->save();
            }
        }
        
        return Redirect::route('purchases.index')->with([
            'success' => 'Achat mis à jour avec succès.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase = Purchase::find($id);
        $payment = Payment::where('purchase_id',$id)->first();
        if($payment->proof_file){
            Storage::disk('local')->delete('/uploads/'.$payment->proof_file);
        }
        Product::where('purchase_id',$id)->delete();
        $payment->delete();
        $purchase->delete();
        return Redirect::route('purchases.index')->with('success','Achat supprimé avec succès.');
    }
}
