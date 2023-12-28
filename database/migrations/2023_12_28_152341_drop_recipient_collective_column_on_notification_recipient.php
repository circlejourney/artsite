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
            $table->dropForeign("notification_recipient_recipient_collective_id_foreign");
            $table->dropColumn("recipient_collective_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_recipient', function (Blueprint $table) {
            $table->unsignedBigInteger("recipient_collective_id");
            $table->foreign("recipient_collective_id")->references("id")->on("collectives")->cascadeOnDelete();
        });
    }
};
