<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable()->comment('Module or category for the FAQ, e.g., login, wallet');
            $table->string('question');
            $table->text('answer');
            $table->enum('status', ['published', 'draft'])->default('published');
            $table->enum('audience', ['user', 'artist', 'both'])->default('both')->comment('Intended audience for this FAQ');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('faqs');
    }
};
