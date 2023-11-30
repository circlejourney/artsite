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
			$table->dropPrimary("id");
			$table->string("name");
        });
		Artisan::call('db:seed', [
			'--class' => 'TagMigrationSeeder',
			'--force' => true
		]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
			$table->renameColumn("name", "id")->primary();
        });
    }
};
