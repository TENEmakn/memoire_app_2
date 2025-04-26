<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LocationRequest;

class MissionController extends Controller
{
    /**
     * Affiche la liste des missions de l'utilisateur connecté
     */
    public function mesMissions(Request $request)
    {
        $user = Auth::user();
        $query = LocationRequest::where('chauffeur_id', $user->id)->orderBy('created_at', 'desc');
        
        // Filtrer par statut si spécifié
        if ($request->has('statut')) {
            $statut = $request->statut;
            $query->where('statut', $statut);
        }
        
        $reservations = $query->get();
        
        return view('autres.mes_missions', [
            'reservations' => $reservations,
        ]);
    }
} 