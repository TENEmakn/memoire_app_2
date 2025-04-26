<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Affiche le formulaire d'édition des documents
     */
    public function edit($type)
    {
        // Vérifier que le type demandé est valide
        if (!in_array($type, ['piece', 'permis'])) {
            return redirect()->route('auth.profil')->with('docs_message', 'Type de document invalide.');
        }
        
        $user = Auth::user();
        $title = ($type === 'piece') ? 'Pièce d\'identité' : 'Permis de conduire';
        
        return view('autres.documents.edit', compact('type', 'title', 'user'));
    }
    
    /**
     * Met à jour les documents d'identité
     */
    public function update(Request $request)
    {
        $type = $request->input('type');
        
        // Vérifier que le type est valide
        if (!in_array($type, ['piece', 'permis'])) {
            return redirect()->route('auth.profil')->with('docs_message', 'Type de document invalide.');
        }
        
        // Validation des fichiers
        $request->validate([
            'document_recto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'document_verso' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'document_recto.required' => 'L\'image recto est obligatoire.',
            'document_verso.required' => 'L\'image verso est obligatoire.',
            'document_recto.image' => 'Le fichier recto doit être une image.',
            'document_verso.image' => 'Le fichier verso doit être une image.',
            'document_recto.mimes' => 'Le fichier recto doit être au format JPEG, PNG ou JPG.',
            'document_verso.mimes' => 'Le fichier verso doit être au format JPEG, PNG ou JPG.',
            'document_recto.max' => 'Le fichier recto ne doit pas dépasser 2 Mo.',
            'document_verso.max' => 'Le fichier verso ne doit pas dépasser 2 Mo.',
        ]);
        
        $user = Auth::user();
        
        // Définir les champs à mettre à jour selon le type de document
        $rectoField = ($type === 'piece') ? 'image_piece_recto' : 'image_permis_recto';
        $versoField = ($type === 'piece') ? 'image_piece_verso' : 'image_permis_verso';
        
        // Supprimer les anciennes images si elles existent
        if ($user->$rectoField) {
            Storage::delete('public/' . $user->$rectoField);
        }
        if ($user->$versoField) {
            Storage::delete('public/' . $user->$versoField);
        }
        
        // Enregistrer les nouvelles images
        $rectoPath = $request->file('document_recto')->store('documents/' . $user->id, 'public');
        $versoPath = $request->file('document_verso')->store('documents/' . $user->id, 'public');
        
        // Mettre à jour les champs dans la base de données
        $user->update([
            $rectoField => $rectoPath,
            $versoField => $versoPath,
        ]);
        
        // Message de succès spécifique au type de document
        if ($type === 'piece') {
            return redirect()->route('auth.profil')->with('profile_success', 'Vos pièces d\'identité ont été soumises avec succès et sont en attente de vérification par notre équipe.');
        } else {
            return redirect()->route('auth.profil')->with('profile_success', 'Votre permis de conduire a été mis à jour avec succès.');
        }
    }
}
