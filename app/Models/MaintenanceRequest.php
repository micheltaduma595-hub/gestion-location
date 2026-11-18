<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRequest extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'Plomberie',
        'Électricité',
        'Serrurerie',
        'Chauffage / climatisation',
        'Structure / toiture',
        'Autre',
    ];

    public const STATUSES = [
        'ouverte' => 'Ouverte',
        'en_cours' => 'En cours',
        'resolue' => 'Résolue',
    ];

    protected $fillable = [
        'user_id',
        'house_id',
        'category',
        'subject',
        'description',
        'status',
        'manager_response',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }
}
