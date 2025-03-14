<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\SupportContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\AlbumController;

Route::get('/', function () {
    return view('welcome');
});
 
// Admin Login
Route::get('/admin-login', function () {
    return view('AdminPanel.Auth.login');
})->name('admin.login');

// User Login
Route::get('/user-login', function () {
    return view('UserPanel.Auth.login');
})->name('user.login');

// Artist Login
Route::get('/artist-login', function () {
    return view('ArtistPanel.Auth.login');
})->name('artist.login');

// Register Pages
Route::view('/admin-register', 'AdminPanel.Auth.register')->name('admin.register');
Route::view('/artist-register', 'ArtistPanel.Auth.register')->name('artist.register');
Route::view('/user-register', 'UserPanel.Auth.register')->name('user.register');

// Authentication Actions
Route::post('/register', [UserController::class, 'store'])->name('register.post');
Route::post('/login',    [UserController::class, 'login'])->name('login.post');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');


Route::get('/albums', [AlbumController::class, 'index'])->name('albums.index');
Route::get('/albums/create', [AlbumController::class, 'create'])->name('albums.create');
Route::post('/albums/store', [AlbumController::class, 'store'])->name('albums.store');


// ---------- Public Booking Routes ----------
// Public booking page 
Route::get('/passbook', [BookingController::class, 'createPublicBooking'])->name('bookings.createPublicBooking');
Route::post('/booking', [BookingController::class, 'storePublicBooking'])->name('bookings.storePublic');
Route::post('/booking/otp/{booking}', [BookingController::class, 'verifyOtp'])->name('bookings.otpVerify');
Route::get('/booking/success', [BookingController::class, 'successPage'])->name('booking.success');


// ---------- Admin Panel Routes ----------
Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard Routes
    Route::get('/admin-dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/overview', [DashboardController::class, 'overview'])->name('admin.dashboard.overview');
    Route::get('/dashboard/statistics', [DashboardController::class, 'statistics'])->name('admin.dashboard.statistics');

    // User Management (Resource Routes)
    Route::resource('users', UserController::class);
    Route::get('users/search', [UserController::class, 'search'])->name('users.search');
    Route::post('users/{user}/update-status', [UserController::class, 'updateStatus'])->name('users.updateStatus');

    // Artist Management 
    Route::resource('artists', ArtistController::class);
    Route::post('artists/{artist}/approve', [ArtistController::class, 'approve'])->name('artists.approve');
    Route::post('artists/{artist}/reject', [ArtistController::class, 'reject'])->name('artists.reject');
    Route::post('artists/{artist}/update-subscription', [ArtistController::class, 'updateSubscription'])->name('artists.updateSubscription');

    // Booking Management – Admin can see full booking list
    Route::get('/bookings', [BookingController::class, 'adminIndex'])->name('bookings.index');
    Route::resource('bookings', BookingController::class)->except(['index']);
    Route::post('bookings/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');

    // Payment Management, Content Management, Categories, Events, Policies, Communication, etc.
    Route::resource('blogs', BlogController::class);
    Route::resource('gallery', GalleryController::class);
    Route::resource('static-pages', StaticPageController::class)->parameters([
        'static-pages' => 'page'
    ])->only(['index', 'edit', 'update']);
    Route::resource('categories', CategoriesController::class);
    Route::resource('events', EventController::class);
    Route::get('policies/{type}', [PolicyController::class, 'edit'])->name('policies.edit');
    Route::put('policies/{type}', [PolicyController::class, 'update'])->name('policies.update');
    Route::get('categories/created-by/{user}', [CategoriesController::class, 'createdBy'])->name('categories.createdBy');
    Route::resource('chats', ChatController::class)->only(['index', 'show']);
    Route::get('queries', [ChatController::class, 'queries'])->name('chats.queries');
    Route::resource('support_contacts', SupportContactController::class);
    Route::resource('faqs', FaqController::class);
    Route::resource('socials', SocialController::class);
});


// ---------- User Panel Routes ----------
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', function () {
        return view('UserPanel.user-layout');
    })->name('user.dashboard');
    Route::get('payments', [PaymentController::class, 'userPayments'])->name('user.payments');
});


// ---------- Artist Panel Routes ----------
Route::middleware(['auth', 'artist'])->group(function () {
    Route::get('/artist-dashboard', function () {
        return view('ArtistPanel.artist-layout');
    })->name('artist.dashboard');
    // Artist booking list  
    Route::get('/artist/bookings', [BookingController::class, 'artistIndex'])->name('artist.bookings.index');
    Route::get('payments', [PaymentController::class, 'artistPayments'])->name('artist.payments');
    Route::get('/complete-profile', [ArtistController::class, 'completeProfile'])->name('artist.completeProfile');
    Route::post('/complete-profile', [ArtistController::class, 'storeProfile'])->name('artist.storeProfile');
});
