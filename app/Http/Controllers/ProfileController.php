<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $messageType = $request->query('message');
        
        if ($messageType === 'docs_required') {
            return view('autres.profil')->with('docs_message', 'Vous devez télécharger vos documents d\'identité pour pouvoir louer un véhicule.');
        }
        
        return view('autres.profil');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'ville' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:255',
        ]);
        
        // Mise à jour des informations
        $user->update($validated);
        
        // Redirection avec message de succès
        return redirect()->route('auth.profil')
            ->with('profile_success', 'Vos informations ont été mises à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        try {
            // Validation des données
            $validated = $request->validate([
                'current_password' => 'required|current_password',
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            // Mise à jour du mot de passe
            $user->password = bcrypt($validated['password']);
            $user->save();
            
            // Redirection avec message de succès et indication d'ouvrir le modal
            return redirect()->route('auth.profil')
                ->with('password_success', 'Votre mot de passe a été mis à jour avec succès.')
                ->with('open_password_modal', true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // En cas d'erreur de validation, rediriger avec les erreurs et rouvrir le modal
            return redirect()->route('auth.profil')
                ->withErrors($e->validator)
                ->withInput()
                ->with('open_password_modal', true);
        }
    }
}
