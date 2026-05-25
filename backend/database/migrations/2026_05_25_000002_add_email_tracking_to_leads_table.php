<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->timestamp('email_queued_at')->nullable()->after('notes');
            $table->timestamp('admin_notified_at')->nullable()->after('email_queued_at');
            $table->string('email_status')->default('pending')->after('admin_notified_at');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['email_queued_at', 'admin_notified_at', 'email_status']);
        });
    }
};
