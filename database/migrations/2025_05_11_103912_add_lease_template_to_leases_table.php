<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leases', function (Blueprint $table) {
            // $table->foreignId('lease_template')->nullable()->constrained('lease_templates')->nullOnDelete();
            $table->string('lease_template')->nullable()->after('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leases', function (Blueprint $table) {
            // $table->dropForeign(['lease_template']);
            $table->dropColumn('lease_template');
        });
    }
};
