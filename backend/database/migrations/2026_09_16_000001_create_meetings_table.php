<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('chat_session_id')->nullable()->constrained('chat_sessions')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('meeting_type')->default('discovery_call'); // discovery_call, strategy_session, demo
            $table->date('scheduled_date');
            $table->string('scheduled_time'); // e.g. 15:00 or 3:00 PM
            $table->string('timezone')->default('UTC');
            $table->text('topic')->nullable();
            $table->string('status')->default('confirmed'); // confirmed, pending, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamp('customer_notified_at')->nullable();
            $table->timestamp('team_notified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
