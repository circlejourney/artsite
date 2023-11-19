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
        Schema::table('users', function (Blueprint $table) {
			$table->string("display_name")->nullable();
            $table->string("avatar")->nullable();
			$table->string("profile_html")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
			if(Schema::hasColumn("users", "display_name")) $table->dropColumn("display_name");
			if(Schema::hasColumn("users", "avatar")) $table->dropColumn("avatar");
			if(Schema::hasColumn("users", "profile_html")) $table->dropColumn("profile_html");
        });
    }
};
