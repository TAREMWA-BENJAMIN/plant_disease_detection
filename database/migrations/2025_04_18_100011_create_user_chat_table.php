<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_chat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_chat_chat_id')->constrained('chat');
            $table->foreignId('user_chat_user_id')->constrained('users');
            $table->timestamp('joined_at')->useCurrent();

            $table->unique(['user_chat_chat_id', 'user_chat_user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_chat');
    }
};

