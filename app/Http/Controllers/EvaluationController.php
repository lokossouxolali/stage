<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluation;
use App\Models\Stage;
use App\Models\User;

class EvaluationController extends Controller
{
    public function index()
    {
        $evaluations = Evaluation::with('stage', 'evaluateur')->paginate(10);
        return view('evaluations.index', compact('evaluations'));
    }

    public function create()
    {
        $stages = Stage::all();
        $evaluateurs = User::where('role', 'enseignant')->get();
        return view('evaluations.create', compact('stages', 'evaluateurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'evaluateur_id' => 'required|exists:users,id',
            'note' => 'required|integer|min:0|max:20',
            'commentaires' => 'required|string',
            'competences_evaluees' => 'required|string',
        ]);

        $data = $request->all();
        $data['date_evaluation'] = now();

        Evaluation::create($data);

        return redirect()->route('evaluations.index')
            ->with('success', 'Évaluation créée avec succès');
    }

    public function show(Evaluation $evaluation)
    {
        $evaluation->load('stage', 'evaluateur');
        return view('evaluations.show', compact('evaluation'));
    }

    public function evaluationsAFaire()
    {
        $evaluations = Evaluation::where('evaluateur_id', auth()->id())->paginate(10);
        return view('evaluations.a-faire', compact('evaluations'));
    }
}
