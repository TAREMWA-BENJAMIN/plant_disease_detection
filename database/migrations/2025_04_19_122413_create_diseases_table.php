<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('diseases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scan_id')->constrained()->onDelete('cascade');
            $table->string('disease_name');
            $table->float('confidence');
            $table->timestamp('detected_at');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('diseases');
    }
}; 