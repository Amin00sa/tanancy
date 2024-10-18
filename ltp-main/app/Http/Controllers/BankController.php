<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBankRequest;
use App\Http\Requests\UpdateBankRequest;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Bank/Index');
    }


    public function fetchbanks(Request $request)
    {
        $total = Bank::count();
        $query = Bank::query();
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('rib', 'like', "%{$search}%");
            });
        }
        $itemsPerPage = $request->input('itemsPerPage');
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
            'banks' => $items,
            'total' => $items->total(),
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBankRequest $request)
    {
        Bank::create($request->validated());
        return Redirect::route('banks.index')->with([
            'success' => 'Banque crée avec succès'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBankRequest $request, Bank $bank)
    {
        $bank->update($request->validated());
        return Redirect::route('banks.index')->with([
            'success' => 'Banque mise à jour avec succès'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        // Check if the bank has associated purchases
        if ($bank->purchases->isEmpty()) {
            // No associated purchases, so it's safe to delete the bank
            $bank->delete();
            return redirect()->route('banks.index')
                ->with('success', 'Banque supprimée avec succès.');
        } else {
            // Bank has associated purchases, handle the situation here
            return redirect()->route('banks.index')
                ->with('error', 'La banque a des achats, vous ne pouvez pas supprimer cette banque.');
        }
    }
}
