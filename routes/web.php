<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\DonationAdminController;
use App\Http\Controllers\Admin\CampaignAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Public Home Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {

    $campaigns = Campaign::where('status','active')
        ->orderBy('id','DESC')
        ->take(6)
        ->get();

    $stats = [
        'donations' => Donation::count(),
        'campaigns' => Campaign::count(),
        'donors'    => User::count()
    ];

    return view('home', compact('campaigns','stats'));
})->name('home');


/*
|--------------------------------------------------------------------------
| Campaigns (Public)
|--------------------------------------------------------------------------
*/
Route::resource('campaigns', CampaignController::class)->only(['index','show']);


/*
|--------------------------------------------------------------------------
| Donations (Public)
|--------------------------------------------------------------------------
*/
Route::get('/donate', [DonationController::class, 'create'])->name('donations.create');
Route::post('/donate', [DonationController::class, 'store'])->name('donations.store');

Route::get('/donations/{donation}', [DonationController::class, 'show'])
    ->name('donations.show');


/*
|--------------------------------------------------------------------------
| Stripe Payment Routes
|--------------------------------------------------------------------------
*/
Route::get('/payment/checkout/{donation}', [PaymentController::class, 'checkout'])
    ->name('payment.checkout');

Route::get('/payment/success', [PaymentController::class, 'success'])
    ->name('payment.success');

Route::get('/payment/cancel', [PaymentController::class, 'cancel'])
    ->name('payment.cancel');


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);

    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::post('/logout', [LogoutController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::view('/dashboard','dashboard')
    ->middleware('auth')
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Campaign management
        Route::resource('campaigns', CampaignAdminController::class);

        // Donation management
        Route::resource('donations', DonationAdminController::class);

        // NEW: Change donation status
        Route::post('/donations/{donation}/status', 
            [DonationAdminController::class, 'changeStatus']
        )->name('donations.changeStatus');

        // Users
        Route::resource('users', UserAdminController::class);

        Route::post('/users/{user}/make-admin', [UserAdminController::class, 'makeAdmin'])
            ->name('users.makeAdmin');

        Route::post('/users/{user}/remove-admin', [UserAdminController::class, 'removeAdmin'])
            ->name('users.removeAdmin');
    });

Route::middleware('auth')->group(function () {
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/{chat}/fetch', [ChatController::class, 'fetch'])->name('chat.fetch');
});
Route::middleware('auth')->group(function () {
    Route::get('/my-donations', [DonationController::class, 'myDonations'])
        ->name('donations.my');
});
