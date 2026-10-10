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

    /**
     * Relation : un locataire peut occuper/louer une ou plusieurs maisons.
     */
    public function maisons()
    {
        return $this->hasMany(House::class, 'locataire_id');
    }
}