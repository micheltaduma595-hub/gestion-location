<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajoute la colonne role uniquement si elle n'existe pas encore
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('client')->after('email');
            }
        });

        // Met automatiquement votre compte actuel en 'admin' si la table et l'utilisateur existent
        if (Schema::hasTable('users')) {
            DB::table('users')->where('email', 'micheltadurna595@gmail.com')->update(['role' => 'admin']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};