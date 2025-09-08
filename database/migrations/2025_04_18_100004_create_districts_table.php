<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->foreignId('region_id')->nullable()->constrained('regions');
            $table->timestamps();
            $table->boolean('flag')->default(1);

        });
    }

    public function down()
    {
        Schema::dropIfExists('districts');
    }
};
