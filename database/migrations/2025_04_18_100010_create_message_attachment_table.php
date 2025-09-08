<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('message_attachment', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('message_id')->constrained('message')->onDelete('cascade');
            $table->text('attachment_url');
            $table->timestamp('uploaded_at')->useCurrent();

            $table->index('message_id', 'idx_attachment_message');
        });
    }

    public function down()
    {
        Schema::dropIfExists('message_attachment');
    }
};
