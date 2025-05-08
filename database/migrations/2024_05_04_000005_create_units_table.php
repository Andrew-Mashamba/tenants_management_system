<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('unit_number');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('floor');
            $table->decimal('size', 10, 2); // in square meters/feet
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->decimal('rent_amount', 10, 2);
            $table->decimal('deposit_amount', 10, 2);
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved'])->default('available');
            $table->foreignId('tenant_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('features')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
};
