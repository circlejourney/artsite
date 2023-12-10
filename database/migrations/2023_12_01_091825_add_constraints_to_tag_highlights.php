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
        Schema::table('tag_highlights', function (Blueprint $table) {
            $table->foreign("tag_id")->references("id")->on("tags")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tag_highlights', function (Blueprint $table) {
			$table->dropForeign("tag_highlights_tag_id_foreign");
        });
    }
};
