<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_id',
        'client_id',
        'start_date',
        'end_date',
        'status',
    ];

    // Un bail concerne une maison
    public function house()
    {
        return $this->belongsTo(House::class);
    }

    // Un bail est attribué à un client (User)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}