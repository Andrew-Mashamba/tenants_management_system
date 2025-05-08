<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('date');
            $table->timestamp('end_date')->nullable();
            $table->string('location')->nullable();
            $table->enum('type', ['property', 'maintenance', 'tenant', 'general']);
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->json('attendees')->nullable(); // Store user IDs or roles that should attend
            $table->boolean('is_public')->default(true);
            $table->json('metadata')->nullable(); // For any additional event data
            $table->timestamps();
            $table->softDeletes();

            $table->index('date');
            $table->index(['property_id', 'date']);
            $table->index(['type', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}; 