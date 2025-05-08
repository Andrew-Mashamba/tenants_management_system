<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tenant_onboarding_workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->enum('status', [
                'application_submitted',
                'documents_pending',
                'documents_uploaded',
                'kyc_verification_pending',
                'kyc_verified',
                'background_check_pending',
                'background_check_completed',
                'lease_pending',
                'lease_signed',
                'payment_pending',
                'completed',
                'rejected'
            ])->default('application_submitted');
            $table->json('completed_steps')->nullable();
            $table->json('pending_steps')->nullable();
            $table->json('workflow_data')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tenant_onboarding_workflows');
    }
};
