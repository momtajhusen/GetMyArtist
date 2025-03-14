<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained('artists')->onDelete('cascade');  
            $table->string('title')->nullable();  
            $table->text('description')->nullable(); 
            $table->enum('media_type', ['image', 'video', 'youtube', 'cloud']);  
            $table->string('storage_path')->nullable();  
            $table->string('media_url')->nullable();  
            $table->string('store')->nullable();  
            $table->integer('duration')->nullable(); 
            $table->bigInteger('size')->nullable();  
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft'); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
