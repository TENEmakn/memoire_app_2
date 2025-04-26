<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Affiche le formulaire de contact
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('autres.contact');
    }

    /**
     * Traite l'envoi du formulaire de contact
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'sujet' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Création du message
            $message = new Message();
            $message->nomcomplet_sender = $request->nom;
            $message->email_sender = $request->email;
            $message->telephone_sender = $request->telephone;
            $message->sujet = $request->sujet;
            $message->message = $request->message;
            $message->statut = 'non_lu';

            // Associer l'expéditeur connecté (correction de l'accès à l'utilisateur connecté)
            $message->sender_id = auth()->id(); // ou Auth::id() si tu préfères

            // Le receiver_id peut être défini plus tard ou laissé à null
            $message->receiver_id = null;

            $message->save(); // Correction de la syntaxe (manquait un point-virgule)

            return redirect()->back()->with('success', 'Votre message a été envoyé avec succès. Veuillez vérifier régulièrement votre boîte à messages.');
        } catch (\Exception $e) {
            // Redirection avec un message d'erreur
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer plus tard.')->withInput();
        }
    }

    public function showmessages()
    {
        // Récupérer les utilisateurs avec le statut 'user'
        $users = User::where('status', 'user')->get();
        
        // Récupérer les derniers messages pour chaque utilisateur
        $messages = Message::whereIn('sender_id', $users->pluck('id'))
            ->select('sender_id', 'nomcomplet_sender', 'email_sender', 'telephone_sender', 'sujet', 'message', 'statut', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('sender_id')
            ->map(function ($group) {
                return $group->first();
            })
            ->values();
            
        return view('admin.messages', compact('messages'));
    }

    public function showConversation($sender_id)
    {
        $user = User::findOrFail($sender_id);
        $messages = Message::where('sender_id', $sender_id)
            ->orWhere('receiver_id', $sender_id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Marquer les messages comme lus
        Message::where('sender_id', $sender_id)
            ->where('statut', 'non_lu')
            ->update(['statut' => 'lu']);

        return view('admin.conversation', compact('user', 'messages'));
    }

    public function reply(Request $request, $sender_id)
    {
        $request->validate([
            'sujet' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $message = new Message();
        $message->sender_id = auth()->id();
        $message->receiver_id = $sender_id;
        $message->nomcomplet_sender = auth()->user()->name;
        $message->email_sender = auth()->user()->email;
        $message->telephone_sender = auth()->user()->phone ?? '';
        $message->sujet = $request->sujet;
        $message->message = $request->message;
        $message->statut = 'non_lu';
        $message->save();

        return redirect()->back()->with('success', 'Message envoyé avec succès');
    }
} 