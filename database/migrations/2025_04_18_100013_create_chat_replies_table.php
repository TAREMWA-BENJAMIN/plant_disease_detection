<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chat_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chat')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('chat_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_replies');
    }
}; 