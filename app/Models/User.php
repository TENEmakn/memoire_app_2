<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'prenom',
        'telephone',
        'date_naissance',
        'commune',
        'ville',
        'image_piece_recto',
        'image_piece_verso',
        'image_permis_recto',
        'image_permis_verso',
        'categorie_permis',
        'piece_verifie',
    ];


  

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     *
     * @param string $role Le rôle à vérifier
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->status === $role;
    }

    /**
     * Vérifie si l'utilisateur est un administrateur
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->status === 'admin';
    }
}
