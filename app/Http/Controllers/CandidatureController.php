<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidature;
use App\Models\Offre;

class CandidatureController extends Controller
{
    public function index()
    {
        $candidatures = Candidature::with('user', 'offre')->paginate(10);
        return view('candidatures.index', compact('candidatures'));
    }

    public function create(Request $request, Offre $offre = null)
    {
        // Si l'offre n'est pas fournie via la route, chercher via le paramètre de requête
        if (!$offre && $request->has('offre_id')) {
            $offre = Offre::findOrFail($request->get('offre_id'));
        }
        
        // Si toujours pas d'offre, rediriger vers la liste des offres
        if (!$offre) {
            return redirect()->route('offres.index')
                ->with('error', 'Veuillez sélectionner une offre pour postuler.');
        }
        
        $offre->load('entreprise');
        return view('candidatures.create', compact('offre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'offre_id' => 'required|exists:offres,id',
            'lettre_motivation' => 'required|string',
            'cv_path' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $data = $request->all();
        $data['etudiant_id'] = auth()->id();
        $data['statut'] = 'en_attente';
        $data['date_candidature'] = now();

        if ($request->hasFile('cv_path')) {
            $data['cv_path'] = $request->file('cv_path')->store('cvs');
        }

        if ($request->hasFile('lettre_recommandation')) {
            $data['lettre_recommandation_path'] = $request->file('lettre_recommandation')->store('lettres_recommandation');
        }

        Candidature::create($data);

        return redirect()->route('candidatures.mes')
            ->with('success', 'Candidature soumise avec succès');
    }

    public function show(Candidature $candidature)
    {
        $candidature->load('user', 'offre');
        return view('candidatures.show', compact('candidature'));
    }

    public function mesCandidatures()
    {
        $candidatures = Candidature::where('etudiant_id', auth()->id())
            ->with('offre')
            ->paginate(10);
        return view('candidatures.mes', compact('candidatures'));
    }

    public function candidaturesRecues()
    {
        $user = auth()->user();
        $candidatures = Candidature::whereHas('offre', function($query) use ($user) {
            $query->where('entreprise_id', $user->entreprise_id);
        })->paginate(10);
        
        return view('candidatures.recues', compact('candidatures'));
    }

    public function accepter(Candidature $candidature)
    {
        $candidature->update(['statut' => 'acceptee']);
        
        return back()->with('success', 'Candidature acceptée');
    }

    public function refuser(Candidature $candidature)
    {
        $candidature->update(['statut' => 'refusee']);
        
        return back()->with('success', 'Candidature refusée');
    }

    public function destroy(Candidature $candidature)
    {
        // Seul l'étudiant propriétaire peut supprimer sa candidature
        if (auth()->id() !== $candidature->etudiant_id) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette candidature');
        }

        // Ne peut supprimer que si la candidature est en attente
        if ($candidature->statut !== 'en_attente') {
            return back()->with('error', 'Vous ne pouvez supprimer que les candidatures en attente');
        }

        $candidature->delete();

        return redirect()->route('candidatures.mes')
            ->with('success', 'Candidature supprimée avec succès');
    }

    public function export()
    {
        // Logique pour l'export des candidatures
        return response()->download('candidatures.csv');
    }
}
