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
        Schema::table('collective_user', function (Blueprint $table) {
            $table->unsignedBigInteger("role_id")->nullable()->default(4);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collective_user', function (Blueprint $table) {
            $table->dropColumn("role_id");
        });
    }
};
