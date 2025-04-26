<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVousVente extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rendez_vous_ventes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicule_id',
        'marque_vehicule',
        'serie_vehicule',
        'nb_places_vehicule',
        'image_vehicule',
        'prix_vehicule',
        'annee_vehicule',
        'transmission_vehicule',
        'carburant_vehicule',
        'date_rdv',
        'heure_rdv',
        'nom_complet',
        'telephone',
        'email',
        'notes',
        'fin_rdv',
        'statut',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_rdv' => 'date',
        'heure_rdv' => 'datetime:H:i',
    ];

    /**
     * Get the vehicle associated with the appointment.
     */
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * Get the user (staff member) associated with the appointment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 