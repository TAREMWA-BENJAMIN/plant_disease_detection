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
        Schema::table('pgt_ai_results', function (Blueprint $table) {
            $table->longText('plant_image')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pgt_ai_results', function (Blueprint $table) {
            $table->string('plant_image', 255)->change();
        });
    }
};
