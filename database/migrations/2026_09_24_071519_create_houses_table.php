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
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Le gestionnaire propriétaire
            
            // 👇 Ajoutez cette ligne pour lier la maison au locataire
            $table->foreignId('locataire_id')->nullable()->constrained('locataires')->onDelete('set null');

            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('address');
            $table->string('image')->nullable();
            $table->enum('status', ['disponible', 'louee'])->default('disponible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};