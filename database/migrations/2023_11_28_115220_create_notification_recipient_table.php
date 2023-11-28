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
        Schema::create('notification_recipient', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger("notification_id")->nullable();
			$table->unsignedBigInteger("recipient_id")->nullable();
			$table->unsignedBigInteger("recipient_collective_id")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_recipient');
    }
};
