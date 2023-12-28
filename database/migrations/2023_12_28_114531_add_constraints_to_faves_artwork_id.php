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
        Schema::table('faves', function (Blueprint $table) {
            $table->dropForeign("faves_user_id_foreign");
            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete();
            $table->dropForeign("faves_artwork_id_foreign");
            $table->foreign("artwork_id")->references("id")->on("artworks")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faves', function (Blueprint $table) {
            $table->dropForeign("faves_user_id_foreign");
            $table->foreign("user_id")->references("id")->on("users")->restrictOnDelete();
            $table->dropForeign("faves_artwork_id_foreign");
            $table->foreign("artwork_id")->references("id")->on("artworks")->restrictOnDelete();
        });
    }
};
