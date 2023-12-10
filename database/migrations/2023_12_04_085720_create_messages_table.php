<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
			/*
			 * Messages can be sent by either individuals or collectives. In the latter case,
			 * the sender user should be recorded nevertheless, to support moderation. 
			 * Messages will be displayed as individual clickable links rather than a chatlog
			 * as I anticipate it being much easier to track whether the messages have been
			 * read yet.
			 * 
			 * Every message can have one sender and one recipient of each type (User/Collective).
			 * 
			 * It should be possible to retrieve all messages sent between the same 2 senders
			 * and recipients, regardless of who was sender or receiver.
			 */
            $table->id();
			$table->unsignedBigInteger("sender_id");
			$table->foreign("sender_id")->references("id")->on("users")->cascadeOnDelete();
			$table->unsignedBigInteger("sender_collective_id")->nullable();
			$table->foreign("sender_collective_id")->references("id")->on("users")->cascadeOnDelete();
			$table->unsignedBigInteger("recipient_id")->nullable();
			$table->foreign("recipient_id")->references("id")->on("users")->cascadeOnDelete();
			$table->unsignedBigInteger("recipient_collective_id")->nullable();
			$table->foreign("recipient_collective_id")->references("id")->on("users")->cascadeOnDelete();
			$table->string("subject")->nullable();
			$table->text("content");
			$table->boolean("read")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
