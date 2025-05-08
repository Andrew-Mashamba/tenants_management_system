<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->unsignedBigInteger('landlord_id')->nullable()->after('id');
            // Uncomment below if landlord_id references a users or landlords table
            // $table->foreign('landlord_id')->references('id')->on('landlords')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('landlord_id');
        });
    }
};
