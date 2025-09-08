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
            $table->text('prevention_tips')->nullable()->after('suggested_solution');
            $table->dropColumn('shared');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pgt_ai_results', function (Blueprint $table) {
            $table->dropColumn('prevention_tips');
            $table->boolean('shared')->default(0)->after('suggested_solution');
        });
    }
};
