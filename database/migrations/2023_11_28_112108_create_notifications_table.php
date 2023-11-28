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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
			$table->string("type"); // Type can be: follow, fave, comment, message, collective-join, collective-follow, collective-comment...
			$table->unsignedBigInteger("sender_id")->nullable(); // All notifs should have only one sender or collective sender
			$table->unsignedBigInteger("sender_collective_id")->nullable();
			$table->unsignedBigInteger("artwork_id")->nullable();
			$table->text("content")->nullable(); // Contains the content of the message if it's a message
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
