<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Consulter toutes les notifications de l'utilisateur
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marquer une notification comme lue
     */
    public function marquerLue(Notification $notification)
    {
        // Vérifier que la notification appartient à l'utilisateur
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette notification');
        }

        $notification->marquerCommeLue();

        return back()->with('success', 'Notification marquée comme lue');
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function marquerToutesLues()
    {
        Notification::where('user_id', auth()->id())
            ->where('lu', false)
            ->update([
                'lu' => true,
                'date_lecture' => now(),
            ]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    /**
     * Supprimer une notification
     */
    public function destroy(Notification $notification)
    {
        // Vérifier que la notification appartient à l'utilisateur
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette notification');
        }

        $notification->delete();

        return back()->with('success', 'Notification supprimée avec succès');
    }

    /**
     * Obtenir le nombre de notifications non lues (API)
     */
    public function nombreNonLues()
    {
        $nombre = Notification::where('user_id', auth()->id())
            ->where('lu', false)
            ->count();

        return response()->json([
            'nombre' => $nombre
        ]);
    }
}
