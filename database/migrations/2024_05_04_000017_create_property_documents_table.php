<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('property_documents')) {
            Schema::create('property_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('property_id')->constrained()->onDelete('cascade');
                $table->foreignId('unit_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('title');
                $table->string('category');
                $table->string('file_path');
                $table->string('file_type');
                $table->integer('file_size');
                $table->text('description')->nullable();
                $table->json('metadata')->nullable();
                $table->boolean('is_public')->default(false);
                $table->date('expiry_date')->nullable();
                $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('property_documents');
    }
};
