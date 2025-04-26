<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class GestionnaireController extends Controller
{
    /**
     * Enregistrer un nouveau gestionnaire dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'required|string|max:20',
            'date_naissance' => 'required|date',
            'commune' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'status' => 'required|string',
            'image_piece_recto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_piece_verso' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'piece_verifie' => 'required|boolean',
        ]);

        try {
            // Préparation des données utilisateur
            $userData = [
                'name' => $request->name,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'date_naissance' => $request->date_naissance,
                'commune' => $request->commune,
                'ville' => $request->ville,
                'password' => Hash::make($request->password),
                'status' => $request->status,
                'piece_verifie' => $request->piece_verifie,
                'email_verified_at' => now(), // L'utilisateur est vérifié par défaut lorsqu'il est créé
            ];

            // Traitement des images pour les pièces d'identité
            if ($request->hasFile('image_piece_recto')) {
                $recto = $request->file('image_piece_recto');
                $rectoName = time() . '_recto.' . $recto->extension();
                $rectoPath = $recto->storeAs('documents/pieces', $rectoName, 'public');
                $userData['image_piece_recto'] = $rectoPath;
            }

            if ($request->hasFile('image_piece_verso')) {
                $verso = $request->file('image_piece_verso');
                $versoName = time() . '_verso.' . $verso->extension();
                $versoPath = $verso->storeAs('documents/pieces', $versoName, 'public');
                $userData['image_piece_verso'] = $versoPath;
            }

            // Création de l'utilisateur avec toutes les données
            $user = User::create($userData);
            
            return redirect()->route('admin.gestionnaire')
                ->with('success', 'Gestionnaire ajouté avec succès');
        } catch (\Exception $e) {
            return redirect()->route('admin.gestionnaire')
                ->with('error', 'Erreur lors de la création du gestionnaire: ' . $e->getMessage());
        }
    }

    /**
     * Rechercher des gestionnaires par email, nom ou prénom.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $gestionnaires = User::where('status', 'gestionnaire')
            ->where(function($query) use ($search) {
                $query->where('email', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%");
            })
            ->get();
            
        return view('admin.gestionnaire', compact('gestionnaires'));
    }

    /**
     * Supprimer un gestionnaire spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gestionnaire = User::where('status', 'gestionnaire')->findOrFail($id);
        $gestionnaire->delete();
        
        return redirect()->route('admin.gestionnaire')
            ->with('success', 'Gestionnaire supprimé avec succès');
    }

    /**
     * Mettre à jour le statut de vérification des pièces d'identité d'un gestionnaire.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateVerification(Request $request, $id)
    {
        $gestionnaire = User::where('status', 'gestionnaire')->findOrFail($id);
        
        $gestionnaire->piece_verifie = $request->has('piece_verifie') ? 1 : 0;
        $gestionnaire->save();
        
        return redirect()->back()
            ->with('success', 'Statut de vérification mis à jour avec succès');
    }
} 