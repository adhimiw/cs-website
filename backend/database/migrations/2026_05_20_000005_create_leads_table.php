<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_session_id')->nullable()->constrained('chat_sessions')->onDelete('set null');
            $table->string('source_type'); // chat, contact_form, manual
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('project_type')->nullable();
            $table->text('plan_or_idea')->nullable();
            $table->string('budget')->nullable();
            $table->string('timeline')->nullable();
            $table->string('lead_status')->default('new'); // new, qualified, contacted, closed_won, closed_lost
            $table->string('ip_address')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('referrer_url')->nullable();
            $table->string('referrer_source')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
