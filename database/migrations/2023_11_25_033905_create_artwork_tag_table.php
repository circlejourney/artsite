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
        Schema::create('artwork_tag', function (Blueprint $table) {
            $artwork_id = $table->unsignedBigInteger("artwork_id");
			$tag_id = $table->string("tag_id");
			
			// Make 2 primary keys to allow each artwork to have that tag once only.
			$table->primary([$artwork_id, $tag_id], "artwork_tag_primary");
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artwork_tag');
    }
};