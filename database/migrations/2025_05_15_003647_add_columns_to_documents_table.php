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
        Schema::table('documents', function (Blueprint $table) {
            // Add any missing columns at the end of the table
            if (!Schema::hasColumn('documents', 'version')) {
                $table->string('version')->nullable();
            }
            if (!Schema::hasColumn('documents', 'expiry_date')) {
                $table->timestamp('expiry_date')->nullable();
            }
            if (!Schema::hasColumn('documents', 'is_archived')) {
                $table->boolean('is_archived')->default(false);
            }
            if (!Schema::hasColumn('documents', 'archived_at')) {
                $table->timestamp('archived_at')->nullable();
            }
            if (!Schema::hasColumn('documents', 'archived_by')) {
                $table->foreignId('archived_by')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn([
                'version',
                'expiry_date',
                'is_archived',
                'archived_at',
                'archived_by'
            ]);
        });
    }
};
