<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            // $table->enum('property_type', ['apartment', 'house', 'commercial', 'land'])
            $table->enum('property_type', ['residential', 'commercial', 'mixed'])
                ->default('residential')
                ->after('name');


            // $table->enum('status', ['active', 'inactive', 'maintenance', ''])
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved'])
                ->default('available')
                ->after('property_type');

            $table->json('amenities')
                ->nullable()
                ->after('description');

            $table->json('images')
                ->nullable()
                ->after('amenities');

            $table->decimal('total_units', 10, 0)
                ->default(0)
                ->after('images');
        });

        Schema::table('units', function (Blueprint $table) {
            // $table->enum('unit_type', ['apartment', 'office', 'retail', 'warehouse', 'other'])
            $table->enum('unit_type', ['apartment','house','condo','townhouse','villa','land','office','retail','industrial','hotel','other'])
                ->default('apartment')
                ->after('name');
            
            $table->json('amenities')
                ->nullable()
                ->after('features');

            $table->timestamp('last_maintenance_date')
                ->nullable()
                ->after('images');

            $table->text('maintenance_notes')
                ->nullable()
                ->after('last_maintenance_date');
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'property_type',
                'status',
                'amenities',
                'images',
                'total_units'
            ]);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn([
                'unit_type',
                'amenities',
                'last_maintenance_date',
                'maintenance_notes',
            ]);
        });
    }
};
