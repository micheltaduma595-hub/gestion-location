<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'locataire_id',
        'house_id',
        'user_id',
        'amount',
        'month_covered',
        'payment_method',
        'reference_reçu',
    ];

    /**
     * Relation : Un paiement appartient à un locataire.
     */
    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    /**
     * Relation : Un paiement concerne une maison.
     */
    public function house()
    {
        return $this->belongsTo(House::class);
    }

    /**
     * Relation : Un paiement a été enregistré par un utilisateur (caissier/admin).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}