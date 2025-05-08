<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create documents table
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type');
            $table->integer('file_size');
            $table->date('expiry_date')->nullable();
            $table->boolean('requires_signature')->default(false);
            $table->boolean('is_signed')->default(false);
            $table->timestamp('signed_at')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Add document expiry alerts table
        Schema::create('document_expiry_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('alert_date');
            $table->boolean('is_sent')->default(false);
            $table->timestamps();
        });

        // Add document versions table
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->string('version_number');
            $table->string('file_path');
            $table->text('changes_description')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Add document categories table
        Schema::create('document_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Add document category pivot table
        Schema::create('document_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->foreignId('document_category_id')->constrained('document_categories')->onDelete('cascade');
            $table->timestamps();
        });

        // Add tenant document access table
        Schema::create('tenant_document_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->boolean('can_view')->default(true);
            $table->boolean('can_download')->default(false);
            $table->boolean('can_share')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_document_access');
        Schema::dropIfExists('document_category');
        Schema::dropIfExists('document_categories');
        Schema::dropIfExists('document_versions');
        Schema::dropIfExists('document_expiry_alerts');
        Schema::dropIfExists('documents');
    }
}; 