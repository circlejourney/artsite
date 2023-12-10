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
        Schema::table('art_invites', function (Blueprint $table) {
			$table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete();
			$table->foreign("artwork_id")->references("id")->on("artworks")->cascadeOnDelete();
			$table->foreign("sender_id")->references("id")->on("users")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('art_invites', function (Blueprint $table) {
            $table->dropForeign("art_invites_user_id_foreign");
			$table->dropForeign("art_invites_artwork_id_foreign");
			$table->dropForeign("art_invites_sender_id_foreign");
        });
    }
};
