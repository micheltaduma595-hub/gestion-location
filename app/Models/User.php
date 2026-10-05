<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'role',  // Ajouté pour gérer le rôle (gestionnaire ou client)
        'phone', // Ajouté pour le numéro de téléphone
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

    // --- Relations Eloquent ---

    // Un gestionnaire peut publier plusieurs maisons
    public function houses()
    {
        return $this->hasMany(House::class);
    }

    // Un client peut faire plusieurs réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'client_id');
    }

    // Un client peut avoir plusieurs baux (locations)
    public function leases()
    {
        return $this->hasMany(Lease::class, 'client_id');
    }
}