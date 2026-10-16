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
            // Ajoute la colonne role (par défaut 'client' pour les nouveaux inscrits)
            $table->string('role')->default('client')->after('email');
        });

        // Met automatiquement votre compte actuel en 'admin' pour ne plus être bloqué
        // (Remplacez l'e-mail ci-dessous par l'e-mail avec lequel vous vous connectez)
        DB::table('users')->where('email', 'micheltadurna595@gmail.com')->update(['role' => 'admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};