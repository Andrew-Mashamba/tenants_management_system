<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chart_of_accounts_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accounting_integration_id')->constrained()->onDelete('cascade');
            $table->string('provider_account_id');
            $table->string('provider_account_name');
            $table->string('system_account');
            $table->timestamps();
            
            $table->unique(['accounting_integration_id', 'provider_account_id'], 'coa_mapping_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chart_of_accounts_mappings');
    }
}; 