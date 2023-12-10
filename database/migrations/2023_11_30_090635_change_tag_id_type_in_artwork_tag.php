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
        Schema::table('artwork_tag', function (Blueprint $table) {
            $table->unsignedBigInteger("tag_id")->change();
			$table->foreign("tag_id")->references("id")->on("tags")->cascadeOnDelete();
        });
		Artisan::call('db:seed', [
			'--class' => 'TagUserSeeder',
			'--force' => true
		]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artwork_tag', function (Blueprint $table) {
			$table->dropForeign("artwork_tag_tag_id_foreign");
            $table->string("tag_id")->change();
        });
    }
};
