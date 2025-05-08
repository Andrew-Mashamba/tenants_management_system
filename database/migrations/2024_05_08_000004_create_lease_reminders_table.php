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
        Schema::create('lease_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lease_id')->constrained()->onDelete('cascade');
            $table->string('type'); // rent_due, lease_expiry, document_expiry, etc.
            $table->string('status')->default('pending'); // pending, sent, cancelled
            $table->timestamp('scheduled_at');
            $table->timestamp('sent_at')->nullable();
            $table->text('message')->nullable();
            $table->json('metadata')->nullable(); // Additional data specific to reminder type
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lease_reminders');
    }
};
