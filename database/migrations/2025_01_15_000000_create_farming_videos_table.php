<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('farming_resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category'); // crop_management, pest_control, soil_health, irrigation, harvesting, etc.
            $table->string('subcategory')->nullable(); // specific crop or technique
            $table->enum('type', ['video', 'pdf', 'document']); // resource type
            $table->string('file_path'); // path to file
            $table->string('thumbnail_path')->nullable(); // path to thumbnail image
            $table->integer('duration_seconds')->nullable(); // video duration in seconds
            $table->integer('page_count')->nullable(); // PDF page count
            $table->string('file_size_mb')->nullable(); // file size in MB
            $table->string('language', 10)->default('en'); // en, sw, lg, etc.
            $table->json('target_regions')->nullable(); // specific regions this resource is relevant for
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_offline_available')->default(true);
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->string('uploaded_by')->nullable(); // admin or expert name
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['category', 'type', 'is_offline_available']);
            $table->index(['language', 'is_offline_available']);
            $table->index('is_featured');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farming_resources');
    }
}; 