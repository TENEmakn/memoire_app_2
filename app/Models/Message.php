<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    
    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'messages';
    
    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomcomplet_sender',
        'email_sender',
        'telephone_sender',
        'sujet',
        'message',
        'statut',
        'sender_id',
        'receiver_id'
    ];
    
    /**
     * Les attributs qui doivent être mutés en dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    
    /**
     * Relation avec l'utilisateur qui a géré ce message (optionnel)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Vérifier si le message a été lu
     */
    public function isRead()
    {
        return $this->statut !== 'non_lu';
    }
    
    /**
     * Vérifier si le message a été traité
     */
    public function isProcessed()
    {
        return $this->statut === 'traite';
    }
}
