<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Payment Settings
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('payment_gateway')->nullable();
            $table->json('gateway_credentials')->nullable();
            $table->decimal('late_fee_percentage', 5, 2)->default(0);
            $table->integer('grace_period_days')->default(5);
            $table->boolean('auto_charge_late_fees')->default(false);
            $table->boolean('send_payment_reminders')->default(true);
            $table->integer('reminder_days_before')->default(3);
            $table->timestamps();
        });

        // Property Custom Fields
        Schema::create('property_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // text, number, date, select, checkbox, etc.
            $table->json('options')->nullable(); // For select fields
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('property_custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->foreignId('field_id')->constrained('property_custom_fields')->onDelete('cascade');
            $table->text('value');
            $table->timestamps();
        });

        // Regional Settings
        Schema::create('regional_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('timezone')->default('UTC');
            $table->string('date_format')->default('Y-m-d');
            $table->string('time_format')->default('H:i');
            $table->string('currency')->default('USD');
            $table->string('language')->default('en');
            $table->string('number_format')->default('1,234.56');
            $table->string('first_day_of_week')->default('monday');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regional_settings');
        Schema::dropIfExists('property_custom_field_values');
        Schema::dropIfExists('property_custom_fields');
        Schema::dropIfExists('payment_settings');
    }
}; 