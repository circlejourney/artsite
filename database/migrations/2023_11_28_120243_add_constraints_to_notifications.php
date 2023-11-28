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
        Schema::table('notifications', function (Blueprint $table) {
			$table->foreign("sender_id")->references("id")->on("users")->cascadeOnDelete();
			$table->foreign("sender_collective_id")->references("id")->on("collectives")->cascadeOnDelete();
			$table->foreign("artwork_id")->references("id")->on("artworks")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            //
        });
    }
};
