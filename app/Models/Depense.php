<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'categorie',
        'montant',
        'motif',
        'date',
        'user_id',
    ];
    
    protected $casts = [
        'date' => 'date',
        'montant' => 'decimal:2',
    ];
    
    /**
     * Get the user who created this expense.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
