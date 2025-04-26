<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Benefice extends Model
{
    use HasFactory;
    
    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'benefices';
    
    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'montant',
        'categorie',
        'remarques',
        'user_id',
        'location_request_id',
        'date',
    ];
    
    /**
     * Les attributs à caster.
     *
     * @var array
     */
    protected $casts = [
        'montant' => 'decimal:2',
        'date' => 'date',
    ];
    
    /**
     * Relation avec l'utilisateur qui a créé l'enregistrement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function locationRequest()
    {
        return $this->belongsTo(LocationRequest::class);
    }
    
    
    
    

}
