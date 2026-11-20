<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locataire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'postnom',
        'prenom',
        'email',
        'telephone',
        'profession',
    ];

    public static function syncFromUser(User $user): self
    {
        $locataire = static::firstOrNew(['email' => $user->email]);
        if (! $locataire->exists) {
            $locataire->fill([
                'nom' => $user->name,
                'postnom' => null,
                'prenom' => '',
                'telephone' => $user->phone ?? '',
                'profession' => 'Non spécifié',
            ]);
        }

        $locataire->save();

        return $locataire;
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

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
