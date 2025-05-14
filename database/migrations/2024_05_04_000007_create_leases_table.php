<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leases', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->json('unit_ids')->nullable();
            // $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('rent_amount', 10, 2);
            $table->decimal('deposit_amount', 10, 2);
            $table->enum('payment_frequency', ['monthly', 'quarterly', 'annually'])->default('monthly');
            $table->enum('status', ['active', 'expired', 'terminated', 'pending'])->default('pending');
            $table->json('terms')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leases');
    }
};
