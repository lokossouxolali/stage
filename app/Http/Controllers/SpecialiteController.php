<?php

namespace App\Http\Controllers;

use App\Models\Specialite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SpecialiteController extends Controller
{
    public function index(): View
    {
        $specialites = Specialite::query()
            ->withCount('enseignants')
            ->orderBy('nom')
            ->paginate(15);

        return view('specialites.index', compact('specialites'));
    }

    public function create(): View
    {
        return view('specialites.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Specialite::create($this->validatedData($request));

        return redirect()->route('specialites.index')
            ->with('success', 'Spécialité / Département créé avec succès.');
    }

    public function edit(Specialite $specialite): View
    {
        return view('specialites.edit', compact('specialite'));
    }

    public function update(Request $request, Specialite $specialite): RedirectResponse
    {
        $specialite->update($this->validatedData($request, $specialite));

        return redirect()->route('specialites.index')
            ->with('success', 'Spécialité / Département mis à jour avec succès.');
    }

    public function destroy(Specialite $specialite): RedirectResponse
    {
        if ($specialite->enseignants()->exists()) {
            return back()->with('error', 'Cette spécialité ne peut pas être supprimée car elle est utilisée par des enseignants.');
        }

        $specialite->delete();

        return redirect()->route('specialites.index')
            ->with('success', 'Spécialité / Département supprimé avec succès.');
    }

    private function validatedData(Request $request, ?Specialite $specialite = null): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:255', Rule::unique('specialites')->ignore($specialite)],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('specialites')->ignore($specialite)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
