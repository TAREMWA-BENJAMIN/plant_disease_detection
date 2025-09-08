<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('message_attachment');
        Schema::dropIfExists('message');
        Schema::dropIfExists('user_chat');
    }

    public function down()
    {
        // Re-create message table
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

        // Re-create message_attachment table
        Schema::create('message_attachment', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('message_id')->constrained('message')->onDelete('cascade');
            $table->text('attachment_url');
            $table->timestamp('uploaded_at')->useCurrent();
            $table->index('message_id', 'idx_attachment_message');
        });

        // Re-create user_chat table
        Schema::create('user_chat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_chat_chat_id')->constrained('chat');
            $table->foreignId('user_chat_user_id')->constrained('users');
            $table->timestamp('joined_at')->useCurrent();
            $table->unique(['user_chat_chat_id', 'user_chat_user_id']);
        });
    }
}; 