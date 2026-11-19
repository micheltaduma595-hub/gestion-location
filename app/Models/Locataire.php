<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locataire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'profession',
    ];

    public static function syncFromUser(User $user): self
    {
        return static::updateOrCreate(
            ['email' => $user->email],
            [
                'nom' => $user->name,
                'prenom' => '',
                'telephone' => $user->phone ?? '',
                'profession' => 'Non spécifié',
            ],
        );
    }

    public static function syncTenantAccounts(): void
    {
        User::whereIn('role', [User::ROLE_CLIENT, User::ROLE_TENANT])
            ->get()
            ->each(fn (User $user) => static::syncFromUser($user));
    }

    /**
     * Relation : un locataire peut occuper/louer une ou plusieurs maisons.
     */
    public function maisons()
    {
        return $this->hasMany(House::class, 'locataire_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}