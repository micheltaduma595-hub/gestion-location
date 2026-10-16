<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    // Autorise l'insertion en masse de ces champs
    protected $fillable = [
        'name',
        'email',
        'message',
    ];
}