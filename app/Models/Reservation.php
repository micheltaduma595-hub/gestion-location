<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_id',
        'client_id',
        'status',
    ];

    // Une réservation concerne une maison
    public function house()
    {
        return $this->belongsTo(House::class);
    }

    // Une réservation est effectuée par un client (User)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}