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
        Schema::create('privacy_levels', function (Blueprint $table) {
            $table->id();
			$table->string("name");
			$table->string("description");
        });
		Artisan::call('db:seed', [
			'--class' => 'PrivacyLevelsSeeder',
			'--force' => true
		]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('privacy_levels');
    }
};
