<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('locataire_id')->constrained('locataires')->onDelete('cascade');
            $table->foreignId('house_id')->constrained('houses')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Le caissier ou l'admin
            $table->decimal('amount', 10, 2); // Montant du loyer payé
            $table->string('month_covered'); // Mois ou période concernée (ex: "Octobre 2026")
            $table->string('payment_method')->default('Espèces'); // Mode de paiement (Espèces, Airtel Money, etc.)
            $table->string('reference_reçu')->unique(); // Numéro unique du reçu (ex: REC-20261012-XXXX)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};