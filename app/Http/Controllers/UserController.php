<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\InscriptionValidee;
use App\Mail\InscriptionRefusee;
use App\Mail\DemandeDirecteurMemoire;
use App\Mail\ReponseDirecteurMemoire;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('entreprise', 'directeurMemoire')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,etudiant,entreprise,enseignant',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,etudiant,entreprise,enseignant',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'role']);

        // Gestion de la photo de profil
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }

            // Stocker la nouvelle photo
            $data['photo_path'] = $request->file('photo')->store('profiles', 'public');
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    public function editProfile()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'telephone']);

        // Gestion de la photo de profil
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }

            // Stocker la nouvelle photo
            $data['photo_path'] = $request->file('photo')->store('profiles', 'public');
        }

        $user->update($data);

        return redirect()->route('profile.show')
            ->with('success', 'Profil mis à jour avec succès');
    }

    public function editPassword()
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('profile.show')
            ->with('success', 'Mot de passe mis à jour avec succès');
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('q');
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'email']);
        
        return response()->json($users);
    }

    public function validerInscription(User $user)
    {
        $user->update(['statut_inscription' => 'valide', 'est_actif' => true]);
        
        // Envoyer un email de confirmation
        try {
            Mail::to($user->email)->send(new InscriptionValidee($user));
            
            // Créer une notification pour l'utilisateur
            Notification::create([
                'user_id' => $user->id,
                'type' => 'inscription_validee',
                'titre' => 'Inscription validée',
                'message' => 'Votre inscription a été validée par l\'administrateur. Vous pouvez maintenant vous connecter à votre compte.',
                'lien' => route('login'),
            ]);
            
            return back()->with('success', 'Inscription validée avec succès. Un email de confirmation a été envoyé à ' . $user->email);
        } catch (\Exception $e) {
            // Log l'erreur mais continue l'exécution
            \Log::error('Erreur lors de l\'envoi de l\'email de validation : ' . $e->getMessage());
            
            // Créer quand même une notification pour l'utilisateur
            Notification::create([
                'user_id' => $user->id,
                'type' => 'inscription_validee',
                'titre' => 'Inscription validée',
                'message' => 'Votre inscription a été validée par l\'administrateur. Vous pouvez maintenant vous connecter à votre compte.',
                'lien' => route('login'),
            ]);
            
            return back()->with('success', 'Inscription validée avec succès. Note : L\'envoi de l\'email a échoué, mais l\'utilisateur a été notifié dans l\'application.');
        }
    }

    public function refuserInscription(User $user)
    {
        $user->update(['statut_inscription' => 'refuse', 'est_actif' => false]);
        
        // Envoyer un email de notification
        try {
            Mail::to($user->email)->send(new InscriptionRefusee($user));
            
            // Créer une notification pour l'utilisateur
            Notification::create([
                'user_id' => $user->id,
                'type' => 'inscription_refusee',
                'titre' => 'Inscription refusée',
                'message' => 'Votre inscription a été refusée par l\'administrateur. Veuillez contacter l\'administration pour plus d\'informations.',
                'lien' => null,
            ]);
            
            return back()->with('success', 'Inscription refusée. Un email de notification a été envoyé à ' . $user->email);
        } catch (\Exception $e) {
            // Log l'erreur mais continue l'exécution
            \Log::error('Erreur lors de l\'envoi de l\'email de refus : ' . $e->getMessage());
            
            // Créer quand même une notification pour l'utilisateur
            Notification::create([
                'user_id' => $user->id,
                'type' => 'inscription_refusee',
                'titre' => 'Inscription refusée',
                'message' => 'Votre inscription a été refusée par l\'administrateur. Veuillez contacter l\'administration pour plus d\'informations.',
                'lien' => null,
            ]);
            
            return back()->with('success', 'Inscription refusée. Note : L\'envoi de l\'email a échoué, mais l\'utilisateur a été notifié dans l\'application.');
        }
    }

    public function choisirDirecteurMemoire(Request $request)
    {
        $request->validate([
            'directeur_memoire_id' => 'required|exists:users,id',
        ]);

        $user = auth()->user();
        
        // Vérifier que l'utilisateur est un étudiant
        if (!$user->isEtudiant()) {
            return back()->with('error', 'Seuls les étudiants peuvent choisir un directeur de mémoire');
        }

        // Vérifier que le directeur choisi est un enseignant
        $directeur = User::findOrFail($request->directeur_memoire_id);
        if (!$directeur->isEnseignant()) {
            return back()->with('error', 'Le directeur de mémoire doit être un enseignant');
        }

        // Mettre à jour avec statut en attente
        $user->update([
            'directeur_memoire_id' => $request->directeur_memoire_id,
            'statut_demande_dm' => 'en_attente',
            'raison_refus_dm' => null,
        ]);

        // Envoyer une notification au directeur de mémoire
        Notification::create([
            'user_id' => $directeur->id,
            'type' => 'demande_encadrement',
            'titre' => 'Nouvelle demande d\'encadrement',
            'message' => $user->name . ' (' . $user->email . ') souhaite que vous soyez son directeur de mémoire.',
            'lien' => route('demandes-encadrement.index'),
        ]);

        // Envoyer un email au directeur de mémoire
        try {
            Mail::to($directeur->email)->send(new DemandeDirecteurMemoire($user, $directeur));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email de demande d\'encadrement : ' . $e->getMessage());
        }

        return redirect()->route('profile.show')
            ->with('success', 'Votre demande d\'encadrement a été envoyée. Vous recevrez une notification lorsque le directeur de mémoire répondra.');
    }

    /**
     * Afficher les demandes d'encadrement pour un enseignant
     */
    public function demandesEncadrement()
    {
        $user = auth()->user();
        
        if (!$user->isEnseignant()) {
            abort(403, 'Accès réservé aux enseignants');
        }

        $demandes = User::where('directeur_memoire_id', $user->id)
            ->where('statut_demande_dm', 'en_attente')
            ->with(['entreprise'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.demandes-encadrement', compact('demandes'));
    }

    /**
     * Accepter une demande d'encadrement
     */
    public function accepterDemandeEncadrement(Request $request, User $etudiant)
    {
        $user = auth()->user();
        
        if (!$user->isEnseignant() || $etudiant->directeur_memoire_id !== $user->id) {
            abort(403, 'Accès non autorisé');
        }

        if ($etudiant->statut_demande_dm !== 'en_attente') {
            return back()->with('error', 'Cette demande a déjà été traitée');
        }

        $etudiant->update([
            'statut_demande_dm' => 'accepte',
        ]);

        // Créer une notification pour l'étudiant
        Notification::create([
            'user_id' => $etudiant->id,
            'type' => 'demande_encadrement_acceptee',
            'titre' => 'Demande d\'encadrement acceptée',
            'message' => $user->name . ' a accepté votre demande d\'encadrement de mémoire.',
            'lien' => route('profile.show'),
        ]);

        // Envoyer un email à l'étudiant
        try {
            Mail::to($etudiant->email)->send(new ReponseDirecteurMemoire($etudiant, $user, true));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email de réponse d\'encadrement : ' . $e->getMessage());
        }

        return back()->with('success', 'Demande d\'encadrement acceptée avec succès');
    }

    /**
     * Refuser une demande d'encadrement
     */
    public function refuserDemandeEncadrement(Request $request, User $etudiant)
    {
        $request->validate([
            'raison' => 'required|string|max:1000',
        ]);

        $user = auth()->user();
        
        if (!$user->isEnseignant() || $etudiant->directeur_memoire_id !== $user->id) {
            abort(403, 'Accès non autorisé');
        }

        if ($etudiant->statut_demande_dm !== 'en_attente') {
            return back()->with('error', 'Cette demande a déjà été traitée');
        }

        $etudiant->update([
            'statut_demande_dm' => 'refuse',
            'raison_refus_dm' => $request->raison,
            'directeur_memoire_id' => null, // Retirer le directeur de mémoire
        ]);

        // Créer une notification pour l'étudiant
        Notification::create([
            'user_id' => $etudiant->id,
            'type' => 'demande_encadrement_refusee',
            'titre' => 'Demande d\'encadrement refusée',
            'message' => $user->name . ' a refusé votre demande d\'encadrement de mémoire. Raison : ' . $request->raison,
            'lien' => route('profile.show'),
        ]);

        // Envoyer un email à l'étudiant
        try {
            Mail::to($etudiant->email)->send(new ReponseDirecteurMemoire($etudiant, $user, false, $request->raison));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email de réponse d\'encadrement : ' . $e->getMessage());
        }

        return back()->with('success', 'Demande d\'encadrement refusée avec succès');
    }

    /**
     * Exporter la liste des utilisateurs (Admin)
     */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:excel,pdf',
            'role' => 'nullable|in:admin,etudiant,enseignant,entreprise,responsable_stages',
            'statut_inscription' => 'nullable|in:valide,en_attente,refuse',
        ]);

        $query = User::with('entreprise', 'directeurMemoire');

        // Filtrer par rôle si spécifié
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Filtrer par statut d'inscription si spécifié
        if ($request->has('statut_inscription') && $request->statut_inscription) {
            $query->where('statut_inscription', $request->statut_inscription);
        }

        $users = $query->get();
        $format = $request->format;

        if ($format === 'excel') {
            // Export Excel avec PhpSpreadsheet
            $roleLabel = $request->role ? ucfirst($request->role) : 'Tous';
            $filename = 'utilisateurs_' . $roleLabel . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Titre
            $sheet->setCellValue('A1', 'Liste des Utilisateurs');
            $sheet->mergeCells('A1:L1');
            $sheet->getStyle('A1')->applyFromArray([
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '2d3748']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ]);
            
            // Informations d'export
            $row = 2;
            $sheet->setCellValue('A' . $row, 'Export généré le ' . now()->format('d/m/Y à H:i'));
            $sheet->getStyle('A' . $row)->getFont()->setSize(10)->getColor()->setRGB('666666');
            $row++;
            
            if ($request->role) {
                $sheet->setCellValue('A' . $row, 'Type d\'utilisateur : ' . ucfirst($request->role));
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                $row++;
            }
            
            if ($request->statut_inscription) {
                $sheet->setCellValue('A' . $row, 'Statut : ' . ucfirst($request->statut_inscription));
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                $row++;
            }
            
            $row++; // Ligne vide
            
            // En-têtes du tableau
            $headers = ['ID', 'Nom', 'Email', 'Téléphone', 'Rôle', 'Statut Inscription', 'Actif', 'Entreprise', 'Directeur Mémoire', 'Niveau Étude', 'Filière', 'Date de création'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $row, $header);
                $sheet->getStyle($col . $row)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2d3748']
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'FFFFFF']
                        ]
                    ]
                ]);
                $col++;
            }
            
            // Données
            $row++;
            foreach ($users as $user) {
                $sheet->setCellValue('A' . $row, $user->id);
                $sheet->setCellValue('B' . $row, $user->name);
                $sheet->setCellValue('C' . $row, $user->email);
                $sheet->setCellValue('D' . $row, $user->telephone ?? '-');
                $sheet->setCellValue('E' . $row, ucfirst($user->role));
                $sheet->setCellValue('F' . $row, ucfirst($user->statut_inscription ?? 'valide'));
                $sheet->setCellValue('G' . $row, $user->est_actif ? 'Oui' : 'Non');
                $sheet->setCellValue('H' . $row, $user->entreprise ? $user->entreprise->nom : '-');
                $sheet->setCellValue('I' . $row, $user->directeurMemoire ? $user->directeurMemoire->name : '-');
                $sheet->setCellValue('J' . $row, $user->niveau_etude ?? '-');
                $sheet->setCellValue('K' . $row, $user->filiere ?? '-');
                $sheet->setCellValue('L' . $row, $user->created_at->format('d/m/Y H:i:s'));
                
                // Style alterné pour les lignes
                $bgColor = ($row % 2 == 0) ? 'F9FAFB' : 'FFFFFF';
                $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $bgColor]
                    ],
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'E5E7EB']
                        ]
                    ]
                ]);
                
                $row++;
            }
            
            // Ajuster la largeur des colonnes
            foreach (range('A', 'L') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Pied de page
            $row++;
            $sheet->setCellValue('A' . $row, 'Total : ' . $users->count() . ' utilisateur(s)');
            $sheet->getStyle('A' . $row)->getFont()->setSize(9)->getColor()->setRGB('666666');
            $row++;
            $sheet->setCellValue('A' . $row, 'Gestion de Stages - Export généré automatiquement');
            $sheet->getStyle('A' . $row)->getFont()->setSize(9)->getColor()->setRGB('666666');
            
            $writer = new Xlsx($spreadsheet);
            $tempFile = tempnam(sys_get_temp_dir(), 'export_');
            $writer->save($tempFile);
            
            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        } else {
            // Export PDF
            $roleLabel = $request->role ? ucfirst($request->role) : 'Tous';
            $filename = 'utilisateurs_' . $roleLabel . '_' . date('Y-m-d_H-i-s') . '.pdf';
            
            $data = [
                'users' => $users,
                'role' => $request->role,
                'statut_inscription' => $request->statut_inscription,
                'date_export' => now()->format('d/m/Y à H:i'),
            ];

            $pdf = Pdf::loadView('users.export-pdf', $data);
            return $pdf->download($filename);
        }
    }

    /**
     * Consulter la liste des enseignants (Étudiant)
     */
    public function listeEnseignants()
    {
        $enseignants = User::where('role', 'enseignant')
            ->where('est_actif', true)
            ->where('statut_inscription', 'valide')
            ->orderBy('name')
            ->get();

        return view('users.liste-enseignants', compact('enseignants'));
    }

    /**
     * Consulter les étudiants encadrés (Enseignant)
     */
    public function etudiantsEncadres()
    {
        $user = auth()->user();
        
        if (!$user->isEnseignant()) {
            abort(403, 'Seuls les enseignants peuvent consulter leurs étudiants encadrés');
        }

        $etudiants = User::where('directeur_memoire_id', $user->id)
            ->with(['propositionsThemes', 'candidatures.offre'])
            ->orderBy('name')
            ->paginate(10);

        return view('users.etudiants-encadres', compact('etudiants'));
    }
}
