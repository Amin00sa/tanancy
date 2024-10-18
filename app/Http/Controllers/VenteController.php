<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVenteRequest;
use App\Http\Requests\UpdateVenteRequest;
use App\Models\Client;
use App\Models\Marche;
use App\Models\Vente;
use App\Models\VenteProduct;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use NumberFormatter;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = Client::all();
        $marches = Marche::all();
        $client = $request->client;
        $marche = $request->marche;
        return Inertia::render('Vente/Index',compact('clients','marches','client','marche'));
    }

    public function fetchventes(Request $request)
    {
        $total = Vente::count();
        $query = Vente::query()->with('client');
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%");
            });
        }
        $itemsPerPage = $request->input('itemsPerPage');
        $query->orderBy('id', 'DESC');
        $client = $request->client;
        if(isset($client)){
            $query->where('client_id',$client);
        }
        $marche = $request->marche;
        if(isset($marche)){
            $query->where('marche_id',$marche);
        }
        $status = $request->status;
        if(isset($status)){
            $query->where('status',$status);
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
            $totaltva += round((float)$item->total * $item->taux_tva*0.01,2);
            $totalttc += round((float)$item->total + (float)$item->total * $item->taux_tva*0.01,2);
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
            'ventes' => $items,
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
        $clients = Client::all();
        $marches = Marche::all();
        return Inertia::render('Vente/Create',compact('clients','marches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVenteRequest $request)
    {
        $data = $request->validated();
        $year = Carbon::now()->format('Y');
        $countventes = Vente::whereBetween('created_at', [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear(),
        ])->withTrashed()->count();
        $totalttc = round($data['total']+$data['total']*$data['taux_tva'] * 0.01,2);
        $parts = explode('.', $totalttc);
        // Initialiser les variables
        $partie_entiere = $parts[0];
        $partie_decimale = isset($parts[1]) ? str_pad($parts[1], 2, '0', STR_PAD_RIGHT) : null;
        $entiere_text = ucwords($this->nombreEnLettres($partie_entiere));
        $decimale_text = $partie_decimale ? ucwords($this->nombreEnLettres($partie_decimale)) : null;
        $total_text = $entiere_text.' Dirhams';
        if(!is_null($decimale_text)){
            $total_text .='et '. $decimale_text .' Centimes';
        }
        $vente = Vente::create([
            'numero' => str_pad($countventes+1, 4, '0', STR_PAD_LEFT) .'/'. $year,
            'client_id' => $data['client_id'],
            'marche_id' => $data['marche_id'] ? $data['marche_id'] : null,
            'objet' => $data['objet'],
            'total' => $data['total'],
            'total_text' => $total_text,
            'taux_tva' => $data['taux_tva'],
            'date' => $data['date'] ? Carbon::parse($data['date'])->format('Y-m-d') : null,
            'status' => $data['status'],
        ]);
        foreach($data['products'] as $product){
            $product = VenteProduct::Create([
                'vente_id' => $vente->id,
                'designation' => $product['designation'],
                'quantity' => $product['quantity'],
                'unity' => $product['unity'],
                'priceperunity' => $product['priceperunity'],
                'subtotal' => round($product['quantity']*$product['priceperunity'],2),
            ]);
        }
        $vente = Vente::find($vente->id);
        $client = Client::where('id',$data['client_id'])->value('name');
        $ice = Client::where('id',$data['client_id'])->value('ice');
        $datapdf = [
            'tenant' => tenant('id'),
            'numero' => $vente->numero,
            'date' => $data['date'] ? Carbon::parse($data['date'])->format('d/m/Y') : '',
            'client' => $client,
            'ice' => $ice,
            'articles' => $vente->products,
            'objet' => $data['objet'],
            'tva' => $data['taux_tva'],
            'totalht' => $data['total'],
            'totalttc' => $totalttc,
            'total_text' => $total_text,
        ];
        $pdf = Pdf::loadView('pdf.facture',$datapdf);
        $hashName = Str::random(40).'.pdf';
        $directory = 'uploads';
        // Check if the directory exists, and create it if not
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }
        $filePath = $pdf->save(storage_path('app/uploads/'.$hashName));
        $vente->proof_file = $hashName;
        $vente->original_file_name = 'Facture_'.preg_replace('/\//', '-', $vente->numero, 1).'.pdf';
        $vente->save();
        return Redirect::route('ventes.index')->with([
            'success' => 'Vente sauvegardée avec succès.'
        ]);
    }


    public function storeclient(Request $request)
    {
        $client = Client::create([
            'name' => $request->name,
            'ice' => $request->ice,
        ]);
        return response()->json([
            'client' => $client
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vente = Vente::with('marche', 'client', 'products')->find($id);
        return Inertia::render('Vente/Show',compact('vente'));
    }

    public function download(string $id){
        $vente = Vente::find($id);
        $path = Storage::path('uploads/'. $vente->proof_file);
        return response()->download($path, $vente->original_file_name);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $clients = Client::all();
        $marches = Marche::all();
        $vente = Vente::with('client','marche','products')->find($id);
        return Inertia::render('Vente/Edit',compact('vente','marches','clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVenteRequest $request, string $id)
    {
        $data = $request->validated();
        $vente = Vente::find($id);
        $totalttc = round($data['total']+$data['total']*$data['taux_tva'] * 0.01,2);
        $parts = explode('.', $totalttc);
        // Initialiser les variables
        $partie_entiere = $parts[0];
        $partie_decimale = isset($parts[1]) ? str_pad($parts[1], 2, '0', STR_PAD_RIGHT) : null;
        $entiere_text = ucwords($this->nombreEnLettres($partie_entiere));
        $decimale_text = $partie_decimale ? ucwords($this->nombreEnLettres($partie_decimale)) : null;
        $total_text = $entiere_text.' Dirhams';
        if(!is_null($decimale_text)){
            $total_text .=' et '. $decimale_text .' Centimes';
        }
        $vente->update([
            'client_id' => $data['client_id'],
            'marche_id' => $data['marche_id'] ? $data['marche_id'] : null,
            'objet' => $data['objet'],
            'total' => $data['total'],
            'total_text' => $total_text,
            'taux_tva' => $data['taux_tva'],
            'date' => $data['date'] ? Carbon::parse($data['date'])->format('Y-m-d') : null,
            'status' => $data['status'],
        ]);
        VenteProduct::where('vente_id',$id)->delete();
        foreach($data['products'] as $product){
            $product = VenteProduct::Create([
                'vente_id' => $vente->id,
                'designation' => $product['designation'],
                'quantity' => $product['quantity'],
                'unity' => $product['unity'],
                'priceperunity' => $product['priceperunity'],
                'subtotal' => round($product['quantity']*$product['priceperunity'],2),
            ]);
        }
        $vente = Vente::find($id);
        $client = Client::where('id',$data['client_id'])->value('name');
        $ice = Client::where('id',$data['client_id'])->value('ice');
        $datapdf = [
            'tenant' => tenant('id'),
            'numero' => $vente->numero,
            'date' => $data['date'] ? Carbon::parse($data['date'])->format('d/m/Y') : '',
            'client' => $client,
            'ice' => $ice,
            'articles' => $vente->products,
            'objet' => $data['objet'],
            'tva' => $data['taux_tva'],
            'totalht' => $data['total'],
            'totalttc' => $totalttc,
            'total_text' => $total_text,
        ];
        $pdf = Pdf::loadView('pdf.facture',$datapdf);
        if($vente->proof_file){
            Storage::disk('local')->delete('/uploads/'.$vente->proof_file);
        }
        $hashName = Str::random(40).'.pdf';
        $directory = 'uploads';
        // Check if the directory exists, and create it if not
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }
        $filePath = $pdf->save(storage_path('app/uploads/'.$hashName));
        $vente->proof_file = $hashName;
        $vente->original_file_name = 'Facture_'.preg_replace('/\//', '-', $vente->numero, 1).'.pdf';
        $vente->save();
        return Redirect::route('ventes.index')->with([
            'success' => 'Vente mis à jour avec succès.'
        ]);
    }

    private function nombreEnLettres($nombre)
    {
        $formatter = new NumberFormatter('fr_FR', NumberFormatter::SPELLOUT);
    
        return $formatter->format($nombre);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vente = Vente::find($id);
        if($vente->proof_file){
            Storage::disk('local')->delete('/uploads/'.$vente->proof_file);
        }
        VenteProduct::where('vente_id',$id)->delete();
        $vente->delete();
        return Redirect::route('ventes.index')->with('success','Vente supprimé avec succès.');
    }
}
