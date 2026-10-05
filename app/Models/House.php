<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'address',
        'image',
        'status',
    ];

    // Une maison appartient à un gestionnaire (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Une maison peut avoir plusieurs réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Une maison peut avoir plusieurs baux
    public function leases()
    {
        return $this->hasMany(Lease::class);
    }
}