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
        Schema::table('collectives', function (Blueprint $table) {
			$table->foreign("privacy_level_id")->references("id")->on("privacy_levels");
			$table->foreign("founder_id")->references("id")->on("users");
			$table->foreign("top_folder_id")->references("id")->on("folders");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collectives', function (Blueprint $table) {
            //
        });
    }
};
