<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('late_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('type'); // fixed, percentage
            $table->integer('grace_period_days')->default(0);
            $table->integer('frequency_days')->default(1); // How often the fee is applied
            $table->decimal('maximum_amount', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('late_fee_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('late_fee_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('charge_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('late_fee_charges');
        Schema::dropIfExists('late_fees');
    }
}; 