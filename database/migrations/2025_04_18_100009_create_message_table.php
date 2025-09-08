<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('message', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('message_chat_id')->constrained('chat')->onDelete('cascade');
            $table->foreignId('message_user_id')->constrained('users')->onDelete('cascade');
            $table->text('message_text')->nullable();
            $table->timestamp('message_datetime')->useCurrent();
            $table->foreignId('message_parent_id')->nullable()->constrained('message')->onDelete('cascade');

            $table->index('message_chat_id', 'idx_message_chat');
            $table->index('message_parent_id', 'idx_message_parent');
        });
    }

    public function down()
    {
        Schema::dropIfExists('message');
    }
};
