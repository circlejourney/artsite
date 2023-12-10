<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tags', function (Blueprint $table) {
			$table->dropColumn("id");
        });
        Schema::table('tags', function (Blueprint $table) {
			$table->id();
        });
		Artisan::call('db:seed', [
			'--class' => 'ArtworkTagMigrationSeeder',
			'--force' => true
		]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
        });
    }
};
