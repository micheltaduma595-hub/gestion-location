<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->date('occurred_on');
            $table->string('payment_method')->nullable();
            $table->timestamps();

            $table->index(['type', 'occurred_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_activities');
    }
};
