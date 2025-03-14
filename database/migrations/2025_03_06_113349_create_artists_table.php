<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtistsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('stage_name')->nullable();
            $table->enum('profile_managed_by', ['artist', 'manager', 'agency', 'family'])->default('artist');
            $table->string('contact_first_name')->nullable();
            $table->string('contact_last_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->json('social_links')->nullable();
            $table->integer('experience_years')->nullable();
            $table->json('portfolio')->nullable();
            $table->json('genre')->nullable();
            $table->json('events')->nullable();
            $table->decimal('booking_rate', 10, 2)->nullable();
            $table->string('location')->nullable();
            $table->json('awards')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('artists');
    }
}
