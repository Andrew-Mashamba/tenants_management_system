<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->enum('availability_status', ['available', 'occupied', 'maintenance', 'reserved'])
                ->default('available')
                ->after('status');

            $table->timestamp('available_from')
                ->nullable()
                ->after('availability_status');

            $table->text('availability_notes')
                ->nullable()
                ->after('available_from');
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->json('settings')
                ->nullable()
                ->after('description');

            $table->string('default_currency')
                ->default('USD')
                ->after('settings');

            $table->string('timezone')
                ->default('UTC')
                ->after('default_currency');

            $table->json('document_categories')
                ->nullable()
                ->after('timezone');
        });
    }

    public function down()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn([
                'availability_status',
                'available_from',
                'availability_notes'
            ]);
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'settings',
                'default_currency',
                'timezone',
                'document_categories'
            ]);
        });
    }
};
