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
			$table->dropForeign("notification_recipient_notification_id_foreign");
            $table->foreign("notification_id")->references("id")->on("notifications")->cascadeOnDelete();
			$table->dropForeign("notification_recipient_recipient_collective_id_foreign");
            $table->foreign("recipient_collective_id")->references("id")->on("collectives")->cascadeOnDelete();
			$table->dropForeign("notification_recipient_recipient_id_foreign");
            $table->foreign("recipient_id")->references("id")->on("users")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_recipient', function (Blueprint $table) {
			$table->dropForeign("notification_recipient_notification_id_foreign");
            $table->foreign("notification_id")->references("id")->on("notifications")->restrictOnDelete();
			$table->dropForeign("notification_recipient_recipient_collective_id_foreign");
            $table->foreign("recipient_collective_id")->references("id")->on("collectives")->restrictOnDelete();
			$table->dropForeign("notification_recipient_recipient_id_foreign");
            $table->foreign("recipient_id")->references("id")->on("users")->restrictOnDelete();
        });
    }
};
