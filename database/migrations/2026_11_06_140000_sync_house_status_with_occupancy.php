<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('houses')
            ->where(function ($query) {
                $query->whereNotNull('locataire_id')
                    ->orWhereExists(function ($query) {
                        $query->selectRaw('1')
                            ->from('leases')
                            ->whereColumn('leases.house_id', 'houses.id')
                            ->where('leases.status', 'actif');
                    });
            })
            ->update(['status' => 'louee']);

        DB::table('houses')
            ->whereNull('locataire_id')
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('leases')
                    ->whereColumn('leases.house_id', 'houses.id')
                    ->where('leases.status', 'actif');
            })
            ->update(['status' => 'disponible']);
    }

    public function down(): void
    {
        // Occupancy status is derived from current lease and tenant assignments.
    }
};