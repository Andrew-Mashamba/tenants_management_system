<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('smart_lock_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('smart_lock_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('access_type');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['smart_lock_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('smart_lock_users');
    }
}; 