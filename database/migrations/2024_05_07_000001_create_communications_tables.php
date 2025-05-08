<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['general', 'maintenance', 'emergency', 'event']);
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_published')->default(false);
            $table->json('target_audience')->nullable(); // ['all', 'tenants', 'owners', 'staff', 'specific_roles']
            $table->json('specific_recipients')->nullable(); // User IDs for specific recipients
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('noticeboard_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['notice', 'event', 'reminder']);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('bulk_messages', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('content');
            $table->enum('channel', ['email', 'sms', 'both']);
            $table->json('recipients')->nullable(); // User IDs or roles
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'failed']);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->json('delivery_status')->nullable(); // Track delivery status per recipient
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bulk_messages');
        Schema::dropIfExists('noticeboard_items');
        Schema::dropIfExists('announcements');
    }
}; 