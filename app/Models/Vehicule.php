<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'marque',
        'serie',
        'annee',
        'immatriculation',
        'prix_vente',
        'prix_location_interieur',
        'prix_location_abidjan',
        'image_principale',
        'image_secondaire',
        'image_tertiaire',
        'disponibilite',
        'type_vehicule',
        'carburant',
        'transmission',
        'nb_places',
        'etat_vehicule',
        'user_id',
        'visibilite'
    ];

    protected $casts = [
        'disponibilite' => 'boolean',
        'prix_vente' => 'decimal:2',
        'prix_location_interieur' => 'decimal:2',
        'prix_location_abidjan' => 'decimal:2',
        'annee' => 'integer',
        'nb_places' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the sales appointments for the vehicle.
     */
    public function rendezVousVentes()
    {
        return $this->hasMany(RendezVousVente::class);
    }
}
