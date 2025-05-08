<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject');
            $table->nullableMorphs('causer');
            $table->json('properties')->nullable();
            $table->string('event')->nullable();
            $table->string('batch_uuid')->nullable();
            $table->timestamps();
            
            $table->index('log_name', 'activities_log_name_index');
            $table->index(['subject_type', 'subject_id'], 'activities_subject_index');
            $table->index(['causer_type', 'causer_id'], 'activities_causer_index');
            $table->index('event', 'activities_event_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
}; 