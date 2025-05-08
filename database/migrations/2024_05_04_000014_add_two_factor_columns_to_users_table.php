<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'two_factor_secret')) {
                $table->text('two_factor_secret')
                    ->after('password')
                    ->nullable();
            }

            if (!Schema::hasColumn('users', 'two_factor_recovery_codes')) {
                $table->text('two_factor_recovery_codes')
                    ->after('two_factor_secret')
                    ->nullable();
            }

            if (!Schema::hasColumn('users', 'two_factor_confirmed_at')) {
                $table->timestamp('two_factor_confirmed_at')
                    ->after('two_factor_recovery_codes')
                    ->nullable();
            }

            if (!Schema::hasColumn('users', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')
                    ->after('two_factor_confirmed_at')
                    ->default(false);
            }

            // Enhanced profile fields
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->after('phone_number');
            }

            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('address');
            }

            if (!Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable()->after('city');
            }

            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable()->after('state');
            }

            if (!Schema::hasColumn('users', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('country');
            }

            if (!Schema::hasColumn('users', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable()->after('postal_code');
            }

            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('profile_photo_path');
            }

            if (!Schema::hasColumn('users', 'preferences')) {
                $table->json('preferences')->nullable()->after('bio');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'two_factor_enabled',
                'phone_number',
                'address',
                'city',
                'state',
                'country',
                'postal_code',
                'profile_photo_path',
                'bio',
                'preferences'
            ]);
        });
    }
};
