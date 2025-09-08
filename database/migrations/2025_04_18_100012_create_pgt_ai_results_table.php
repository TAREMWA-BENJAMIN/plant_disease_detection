<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pgt_ai_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('plant_image'); // e.g., image path or URL
            $table->string('plant_name');
            $table->enum('status', ['healthy', 'infected']);
            $table->string('disease_name')->nullable();
            $table->text('disease_details')->nullable();
            $table->text('suggested_solution')->nullable();
            $table->boolean('shared')->default(0); // 1 = shared, 0 = not shared
            $table->timestamps();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('pgt_ai_results');
    }
};
