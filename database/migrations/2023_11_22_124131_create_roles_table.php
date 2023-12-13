<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
	 * view_users_list		See a list of every user on the site
	 * manager_users		Edit users' names, profiles, ban users, close accounts
	 * manage_artworks		Edit and delete other users' artworks
	 * change_own_flair		Change the FA icon shown next to name. Fun customisation for founders/donors
	 * default_flair		Font Awesome icon to be displayed next to name by default
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
			$table->string("name");
			$table->boolean("manage_users")->default(false);
			$table->boolean("manage_roles")->default(false);
			$table->boolean("manage_artworks")->default(false);
			$table->boolean("change_own_flair")->default(false);
			$table->string("default_flair")->default("user");
            $table->timestamps();
        });
		Artisan::call('db:seed', [
			'--class' => 'RolesSeeder',
			'--force' => true
		]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
