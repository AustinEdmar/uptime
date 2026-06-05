<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EndpointStoreController;
use App\Http\Controllers\EndpointDestroyController;
use App\Http\Controllers\EndpointUpdateController;
use App\Http\Controllers\EndpointIndexController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteDeleteController;
use App\Http\Controllers\SiteStoreController;
use App\Http\Controllers\SiteNotificationsEmailStoreController;
use App\Http\Controllers\SiteNotificationsEmailDestroyController;
use App\Models\Check;
use App\Events\EndpointWentDown;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return redirect()->route('dashboard');
});

Route::get('/mail-test', function () {
    Mail::to('teste@exemplo.com')->send(new \App\Mail\Example());
    return 'Email enviado';
});



Route::get('/dashboard/{site?}', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/sites', SiteStoreController::class)->middleware(['auth', 'verified']);

Route::post('/sites/{site}/endpoints', EndpointStoreController::class)->middleware(['auth', 'verified']);


Route::delete('/sites/{site}', SiteDeleteController::class)->middleware(['auth', 'verified']);

Route::patch('/endpoints/{endpoint}', EndpointUpdateController::class)->middleware(['auth', 'verified']);

Route::get('/endpoints/{endpoint}', EndpointIndexController::class)->middleware(['auth', 'verified']);

Route::delete('/endpoints/{endpoint}', EndpointDestroyController::class)->middleware(['auth', 'verified']);

Route::post('/sites/{site}/notifications/emails', SiteNotificationsEmailStoreController::class)->middleware(['auth', 'verified']);

Route::delete('/sites/{site}/notifications/emails', SiteNotificationsEmailDestroyController::class)->middleware(['auth', 'verified']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
