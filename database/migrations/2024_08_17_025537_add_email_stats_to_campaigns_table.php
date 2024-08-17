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
        Schema::table('campaigns', function (Blueprint $table) {
            $table->integer('sent_emails')->default(0)->after('processed_contacts');
            $table->integer('failed_emails')->default(0)->after('sent_emails');
            $table->integer('in_progress_emails')->default(0)->after('failed_emails');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['sent_emails', 'failed_emails', 'in_progress_emails']);
        });
    }
};
