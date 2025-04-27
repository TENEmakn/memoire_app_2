<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientMessageController extends Controller
{
    public function index()
    {
        // Récupérer les derniers messages par sujet pour le client connecté
        $conversations = Message::where('receiver_id', Auth::id())
            ->orWhere('sender_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('sujet')
            ->map(function ($messages) {
                return $messages->first();
            });

        return view('client.messages', compact('conversations'));
    }

    public function show($sujet)
    {
        // Récupérer tous les messages liés au sujet pour le client connecté
        $messages = Message::where('sujet', $sujet)
            ->where(function ($query) {
                $query->where('receiver_id', Auth::id())
                    ->orWhere('sender_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Marquer les messages comme lus
        Message::where('receiver_id', Auth::id())
            ->where('sujet', $sujet)
            ->where('statut', 'non_lu')
            ->update(['statut' => 'lu']);

        return view('client.conversation', compact('messages', 'sujet'));
    }

    public function reply(Request $request, $sujet)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $user = Auth::user();

        Message::create([
            'sender_id' => $user->id,
            'receiver_id' => 1, // ID de l'entreprise (à ajuster selon votre configuration)
            'sujet' => $sujet,
            'message' => $request->message,
            'statut' => 'non_lu',
            'nomcomplet_sender' => $user->name,
            'email_sender' => $user->email,
            'telephone_sender' => $user->phone ?? ''
        ]);

        return redirect()->back()->with('success', 'Message envoyé avec succès');
    }
} 