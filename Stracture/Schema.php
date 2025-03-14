<?php

// ----------------------------------------
// 1. CORE TABLES
// ----------------------------------------

// USERS TABLE (Core: All roles are stored here)
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->enum('role', ['admin', 'user', 'artist'])->default('user');
    $table->rememberToken();
    $table->timestamps();
});

// ----------------------------------------
// 2. CATEGORY MANAGEMENT
// ----------------------------------------

// CATEGORIES TABLE (For organizing artists, events, etc.)
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->unsignedBigInteger('parent_id')->nullable();
    $table->string('status')->default('active'); 
    $table->string('image')->nullable(); 
    $table->unsignedBigInteger('created_by'); 
    $table->timestamps();
    $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
    $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
});

// EVENTS TABLE (For organizing artists, events, etc.)
Schema::create('events', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->dateTime('event_date');
    $table->string('location')->nullable();
    $table->unsignedBigInteger('category_id')->nullable();
    $table->unsignedBigInteger('artist_id')->nullable();
    $table->unsignedBigInteger('created_by');
    $table->enum('status', ['active', 'cancelled', 'completed'])->default('active');
    $table->timestamps();

    $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
    $table->foreign('artist_id')->references('id')->on('artists')->onDelete('set null');
    $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
});


// ----------------------------------------
// 3. ARTIST MANAGEMENT
// ----------------------------------------

// ARTISTS TABLE (Stores artist-specific details)
// This table depends on the USERS and CATEGORIES table.
Schema::create('artists', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('category_id')->nullable();
    $table->string('stage_name')->nullable();  // Public profile name
    $table->enum('profile_managed_by', ['artist', 'manager', 'agency', 'family'])->default('artist');
    $table->string('contact_first_name')->nullable();
    $table->string('contact_last_name')->nullable();
    
    $table->text('bio')->nullable();
    $table->string('profile_photo')->nullable();
    $table->boolean('is_premium')->default(false);
    $table->json('social_links')->nullable();
    $table->integer('experience_years')->nullable();
    $table->json('portfolio')->nullable();
    
    $table->string('genre')->nullable();
    $table->json('events')->nullable();
    
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
});

// ----------------------------------------
// 4. BOOKING & PAYMENT SYSTEM
// ----------------------------------------

// BOOKINGS TABLE (Stores artist booking information)
// Depends on USERS (customer) and ARTISTS.
Schema::create('bookings', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('customer_id');
    $table->unsignedBigInteger('artist_id');
    $table->dateTime('event_date');
    $table->string('event_type');
    $table->text('details')->nullable();
    $table->enum('booking_status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
    $table->timestamps();
    $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
});

// PAYMENTS TABLE (Handles payment transactions for bookings)
Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('booking_id');
    $table->string('payment_method');
    $table->decimal('amount', 8, 2);
    $table->string('transaction_id')->unique();
    $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending');
    $table->timestamps();
    
    $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
});

// ----------------------------------------
// 5. SUBSCRIPTION SYSTEM
// ----------------------------------------

// SUBSCRIPTION PLANS TABLE (Defines available plans for artists)
Schema::create('subscription_plans', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->decimal('price', 8, 2);
    $table->integer('duration'); // Duration in days or months
    $table->json('features')->nullable();
    $table->timestamps();
});

// ARTIST SUBSCRIPTIONS TABLE (Tracks which artist has subscribed to which plan)
Schema::create('artist_subscriptions', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('artist_id');
    $table->unsignedBigInteger('plan_id');
    $table->date('start_date');
    $table->date('end_date');
    $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
    $table->timestamps();
    
    $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
    $table->foreign('plan_id')->references('id')->on('subscription_plans')->onDelete('cascade');
});

// ----------------------------------------
// 6. MEDIA & CONTENT MANAGEMENT
// ----------------------------------------

// VIDEOS TABLE (Stores video bytes for artist promotions)
Schema::create('videos', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('artist_id')->nullable();
    $table->string('video_url');
    $table->string('title')->nullable();
    $table->text('description')->nullable();
    $table->timestamps();
    
    $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
});

// GALLERY TABLE (Stores images and video media for events or portfolios)
Schema::create('gallery', function (Blueprint $table) {
    $table->id();
    $table->string('media_url');
    $table->enum('media_type', ['image', 'video']);
    $table->text('description')->nullable();
    $table->timestamps();
});

// BLOGS TABLE (Content for SEO and information sharing)
Schema::create('blogs', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->longText('content');
    $table->string('meta_title')->nullable();
    $table->text('meta_description')->nullable();
    $table->unsignedBigInteger('author_id')->nullable();
    $table->timestamps();
    
    $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
});

// ----------------------------------------
// 7. FEEDBACK & COMMUNICATION
// ----------------------------------------

// REVIEWS TABLE (Customer reviews for bookings/artists)
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('booking_id');
    $table->unsignedBigInteger('customer_id');
    $table->unsignedBigInteger('artist_id');
    $table->integer('rating');
    $table->text('review')->nullable();
    $table->timestamps();
    
    $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
    $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
});

// CHATS TABLE (Stores live chat messages between users)
Schema::create('chats', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('sender_id');
    $table->unsignedBigInteger('receiver_id');
    $table->text('message');
    $table->boolean('read_status')->default(false);
    $table->timestamps();
    
    $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
});

// CONTACT QUERIES TABLE (Stores queries submitted by users or guests)
Schema::create('contact_queries', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('contact_number')->nullable();
    $table->text('message');
    $table->string('query_type')->nullable();
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
});


php artisan make:model User -mcr
php artisan make:model Category -mcr
php artisan make:model Event -mcr
php artisan make:model Artist -mcr
php artisan make:model Booking -mcr
php artisan make:model Payment -mcr
php artisan make:model SubscriptionPlan -mcr
php artisan make:model ArtistSubscription -mcr
php artisan make:model Video -mcr
php artisan make:model Gallery -mcr
php artisan make:model Blog -mcr
php artisan make:model Review -mcr
php artisan make:model Chat -mcr
php artisan make:model ContactQuery -mcr


 