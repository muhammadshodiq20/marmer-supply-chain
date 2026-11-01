<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Notification::latest();

        if ($user) {
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNull('user_id'); // Notifikasi broadcast sistem
            });
        } else {
            $query->whereNull('user_id');
        }

        $notifications = $query->take(20)->get();
        $unreadCount = $notifications->where('is_read', false)->count();

        // Jika dibuka langsung lewat URL browser (bukan fetch AJAX di latar belakang)
        if (!$request->expectsJson() && !$request->ajax()) {
            $redirectRoute = auth()->check() 
                ? (auth()->user()->isBuyer() ? 'catalog' : 'dashboard') 
                : 'landing';

            return redirect()->route($redirectRoute)
                ->with('info', "Pusat Notifikasi: Anda memiliki {$unreadCount} pesan aktivitas yang dapat dibuka melalui ikon lonceng di menu atas.");
        }

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $user = auth()->user();
        Notification::where(function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhereNull('user_id');
        })->where('is_read', false)->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
