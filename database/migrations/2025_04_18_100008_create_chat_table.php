<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chat', function (Blueprint $table) {
            $table->id('id');
            $table->string('chat_topic', 250);
            $table->text('content');
            $table->foreignId('chat_creator_id')->constrained('users')->onDelete('cascade'); // Reference to 'id' column of 'users' table
            $table->timestamp('chat_created_at')->useCurrent();

            $table->index('chat_topic', 'idx_chat_topic');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat');
    }
};
