<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tenant_kyc_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->string('id_type')->nullable(); // passport, driver's license, national ID, etc.
            $table->string('id_number')->nullable();
            $table->date('id_expiry_date')->nullable();
            $table->string('id_document_path')->nullable();
            $table->string('proof_of_income_path')->nullable();
            $table->string('employment_verification_path')->nullable();
            $table->string('background_check_reference')->nullable();
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tenant_kyc_verifications');
    }
};
