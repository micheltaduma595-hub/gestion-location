<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leases', function (Blueprint $table) {
            $table->timestamp('expiration_reminder_sent_at')->nullable();
            $table->timestamp('expiration_overdue_sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('leases', function (Blueprint $table) {
            $table->dropColumn(['expiration_reminder_sent_at', 'expiration_overdue_sent_at']);
        });
    }
};