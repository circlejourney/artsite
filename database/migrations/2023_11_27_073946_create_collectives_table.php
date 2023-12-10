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
		/*
		 * Columns: id, name, avatar, profile_html
		 * Foreign: BelongsToMany members (User), BelongsToMany mods (User), BelongsToMany artworks (Artwork),
		 * HasMany folders (Folder), HasOne privacy_level_id (PrivacyLevel), HasOne top_folder_id (Folder)
		*/
        Schema::create('collectives', function (Blueprint $table) {
            $table->id();
			$table->string("url")->unique();
			$table->string("display_name");
			$table->string("avatar")->nullable();
			$table->string("profile_html")->nullable();
			$table->unsignedBigInteger("founder_id");
			$table->unsignedBigInteger("privacy_level_id");
			$table->unsignedBigInteger("top_folder_id")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collectives');
    }
};
