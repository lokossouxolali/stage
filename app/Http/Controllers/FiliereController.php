<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FiliereController extends Controller
{
    public function index()
    {
        $filieres = Filiere::withCount(['etudiants', 'offres'])->orderBy('nom')->paginate(15);

        return view('filieres.index', compact('filieres'));
    }

    public function create()
    {
        return view('filieres.create');
    }

    public function store(Request $request)
    {
        Filiere::create($this->validatedData($request));

        return redirect()->route('filieres.index')
            ->with('success', 'Filière créée avec succès.');
    }

    public function edit(Filiere $filiere)
    {
        return view('filieres.edit', compact('filiere'));
    }

    public function update(Request $request, Filiere $filiere)
    {
        $filiere->update($this->validatedData($request, $filiere));

        return redirect()->route('filieres.index')
            ->with('success', 'Filière mise à jour avec succès.');
    }

    public function destroy(Filiere $filiere)
    {
        if ($filiere->etudiants()->exists() || $filiere->offres()->exists()) {
            return back()->with('error', 'Cette filière ne peut pas être supprimée car elle est utilisée par des étudiants ou des offres.');
        }

        $filiere->delete();

        return redirect()->route('filieres.index')
            ->with('success', 'Filière supprimée avec succès.');
    }

    private function validatedData(Request $request, ?Filiere $filiere = null): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:255', Rule::unique('filieres')->ignore($filiere)],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('filieres')->ignore($filiere)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
