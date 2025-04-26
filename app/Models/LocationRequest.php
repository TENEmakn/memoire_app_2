<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicule_id',
        'chauffeur_id',
        'marque_vehicule',
        'serie_vehicule',
        'nom_chauffeur',
        'prenom_chauffeur',
        'numero_telephone_chauffeur',
        'date_debut',
        'heure_depart',
        'date_fin',
        'lieu_depart',
        'point_depart',
        'lieu_arrivee',
        'prix_total',
        'statut',
        'mode_paiement',
        'notes',
        'numero_telephone',
        'email',
        'nom',
        'prenom',
        'reference_paiement'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'prix_total' => 'decimal:2',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function chauffeur()
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }
}
