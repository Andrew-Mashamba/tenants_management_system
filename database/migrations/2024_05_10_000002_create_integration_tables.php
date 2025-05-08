<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Verification Services
        Schema::create('verification_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('service_type'); // id_verification, credit_scoring
            $table->string('provider'); // experian, equifax, etc.
            $table->json('credentials')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('tenant_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('verification_service_id')->constrained('verification_services')->onDelete('cascade');
            $table->string('verification_type'); // id, credit
            $table->json('verification_data')->nullable();
            $table->string('status'); // pending, approved, rejected
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_verifications');
        Schema::dropIfExists('verification_services');
    }
}; 