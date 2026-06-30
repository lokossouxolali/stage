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
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette notification');
        }

        $notification->marquerCommeLue();
        cache()->forget('notif_count_' . auth()->id());

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

        cache()->forget('notif_count_' . auth()->id());

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    /**
     * Supprimer une notification
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette notification');
        }

        $notification->delete();
        cache()->forget('notif_count_' . auth()->id());

        return back()->with('success', 'Notification supprimée avec succès');
    }

    /**
     * Obtenir le nombre de notifications non lues (API)
     */
    public function nombreNonLues()
    {
        $userId = auth()->id();
        $nombre = cache()->remember(
            'notif_count_' . $userId,
            30,
            fn () => Notification::where('user_id', $userId)->where('lu', false)->count()
        );

        return response()->json(['nombre' => $nombre]);
    }

    public function derniereNonLue()
    {
        $notification = Notification::where('user_id', auth()->id())
            ->where('lu', false)
            ->latest()
            ->first();

        return response()->json([
            'notification' => $notification ? [
                'titre' => $notification->titre,
                'message' => $notification->message,
                'lien' => $notification->lien,
            ] : null,
        ]);
    }
}
