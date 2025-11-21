<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entreprise;
use App\Models\User;

class EntrepriseController extends Controller
{
    public function index()
    {
        $entreprises = Entreprise::with('users')->paginate(10);
        return view('entreprises.index', compact('entreprises'));
    }

    public function create()
    {
        return view('entreprises.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'secteur_activite' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Entreprise::create($request->all());

        return redirect()->route('entreprises.index')
            ->with('success', 'Entreprise créée avec succès');
    }

    public function show(Entreprise $entreprise)
    {
        $entreprise->load('users', 'offres');
        return view('entreprises.show', compact('entreprise'));
    }

    public function edit(Entreprise $entreprise)
    {
        return view('entreprises.edit', compact('entreprise'));
    }

    public function update(Request $request, Entreprise $entreprise)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'secteur_activite' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $entreprise->update($request->all());

        return redirect()->route('entreprises.index')
            ->with('success', 'Entreprise mise à jour avec succès');
    }

    public function destroy(Entreprise $entreprise)
    {
        $entreprise->delete();

        return redirect()->route('entreprises.index')
            ->with('success', 'Entreprise supprimée avec succès');
    }

    public function export()
    {
        // Logique pour l'export des entreprises
        return response()->download('entreprises.csv');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $entreprises = Entreprise::where('nom', 'like', "%{$query}%")
            ->orWhere('secteur_activite', 'like', "%{$query}%")
            ->paginate(10);
        
        return view('entreprises.index', compact('entreprises', 'query'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('q');
        $entreprises = Entreprise::where('nom', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'nom']);
        
        return response()->json($entreprises);
    }
}
