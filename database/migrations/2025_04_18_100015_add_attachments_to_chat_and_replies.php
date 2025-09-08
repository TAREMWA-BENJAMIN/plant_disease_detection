<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('chat', function (Blueprint $table) {
            $table->string('attachment_url')->nullable();
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
        });
        Schema::table('chat_replies', function (Blueprint $table) {
            $table->string('attachment_url')->nullable();
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
        });
    }

    public function down()
    {
        Schema::table('chat', function (Blueprint $table) {
            $table->dropColumn(['attachment_url', 'file_type', 'file_size']);
        });
        Schema::table('chat_replies', function (Blueprint $table) {
            $table->dropColumn(['attachment_url', 'file_type', 'file_size']);
        });
    }
}; 