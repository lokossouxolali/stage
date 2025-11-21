<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soutenance;
use App\Models\Stage;
use App\Models\User;

class SoutenanceController extends Controller
{
    public function index()
    {
        $soutenances = Soutenance::with('stage', 'jury')->paginate(10);
        return view('soutenances.index', compact('soutenances'));
    }

    public function create()
    {
        $stages = Stage::all();
        $jury = User::whereIn('role', ['enseignant', 'admin'])->get();
        return view('soutenances.create', compact('stages', 'jury'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'date_soutenance' => 'required|date|after:now',
            'lieu' => 'required|string|max:255',
            'jury_ids' => 'required|array|min:1',
            'jury_ids.*' => 'exists:users,id',
        ]);

        $data = $request->all();
        $data['statut'] = 'planifiee';

        $soutenance = Soutenance::create($data);
        $soutenance->jury()->attach($request->jury_ids);

        return redirect()->route('soutenances.index')
            ->with('success', 'Soutenance planifiée avec succès');
    }

    public function show(Soutenance $soutenance)
    {
        $soutenance->load('stage', 'jury');
        return view('soutenances.show', compact('soutenance'));
    }

    public function demarrer(Soutenance $soutenance)
    {
        $soutenance->update(['statut' => 'en_cours']);
        
        return back()->with('success', 'Soutenance démarrée');
    }

    public function terminer(Soutenance $soutenance)
    {
        $soutenance->update(['statut' => 'terminee']);
        
        return back()->with('success', 'Soutenance terminée');
    }

    public function annuler(Soutenance $soutenance)
    {
        $soutenance->update(['statut' => 'annulee']);
        
        return back()->with('success', 'Soutenance annulée');
    }
}
