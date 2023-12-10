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
        Schema::table('notification_recipient', function (Blueprint $table) {
			$table->foreign("notification_id")->references("id")->on("notifications");
			$table->foreign("recipient_id")->references("id")->on("users");
			$table->foreign("recipient_collective_id")->references("id")->on("collectives");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_recipient', function (Blueprint $table) {
            //
        });
    }
};
