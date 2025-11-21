<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stage;
use App\Models\Candidature;
use App\Models\User;

class StageController extends Controller
{
    public function index()
    {
        $stages = Stage::with('candidature', 'encadreurEntreprise', 'encadreurAcademique')->paginate(10);
        return view('stages.index', compact('stages'));
    }

    public function show(Stage $stage)
    {
        $stage->load('candidature', 'encadreurEntreprise', 'encadreurAcademique', 'rapports', 'evaluations', 'soutenances');
        return view('stages.show', compact('stage'));
    }

    public function stagesEncadres()
    {
        $stages = Stage::where('encadreur_academique_id', auth()->id())->paginate(10);
        return view('stages.encadres', compact('stages'));
    }

    public function export()
    {
        // Logique pour l'export des stages
        return response()->download('stages.csv');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $stages = Stage::whereHas('candidature', function($q) use ($query) {
            $q->whereHas('offre', function($q2) use ($query) {
                $q2->where('titre', 'like', "%{$query}%");
            });
        })->paginate(10);
        
        return view('stages.index', compact('stages', 'query'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('q');
        $stages = Stage::whereHas('candidature', function($q) use ($query) {
            $q->whereHas('offre', function($q2) use ($query) {
                $q2->where('titre', 'like', "%{$query}%");
            });
        })->limit(10)->get(['id', 'candidature_id']);
        
        return response()->json($stages);
    }
}
