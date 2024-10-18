<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMarcheRequest;
use App\Http\Requests\UpdateMarcheRequest;
use App\Models\Marche;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

use function PHPSTORM_META\map;

class MarcheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Marche/Index',);
    }

    public function fetchmarches(Request $request)
    {
        $total = Marche::count();
        $query = Marche::query();
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('maitre_ouvrage', 'like', "%{$search}%")
                  ->orWhere('ville', 'like', "%{$search}%")
                  ->orWhere('date_service', 'like', "%{$search}%")
                  ->orWhere('montant', 'like', "%{$search}%");
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
            'marches' => $items,
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
    public function store(StoreMarcheRequest $request)
    {
        Marche::create($request->validated());
        return Redirect::route('marches.index')->with([
            'success' => 'Marché sauvegardé avec succès.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMarcheRequest $request, int $id)
    {
        $marche = Marche::findOrFail($id);
        $marche->update($request->validated());
        return redirect()->route('marches.index')->with([
            'success' => 'Marché mis à jour avec succès.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $marche = Marche::find($id);
        if ($marche->purchases->isEmpty() && $marche->ventes->isEmpty()) {
            $marche->delete();
            return redirect()->route('marches.index')
                ->with('success', 'Marché supprimée avec succès.');
        } else {
            return redirect()->route('marches.index')
                ->with('error', 'Ce marché a des achats ou des ventes, vous ne pouvez pas le supprimer.');
        }
    }
}
