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
        Schema::table('artworks_users', function (Blueprint $table) {
			$table->foreign('users_id')->references('id')
				->on('users')->onDelete('cascade');
			$table->foreign('artworks_id')->references('id')
				->on('artworks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artworks_users', function (Blueprint $table) {
            //
        });
    }
};
