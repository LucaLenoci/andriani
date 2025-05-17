<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdesioniController;
use App\Http\Controllers\EventiController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
    Route::get('adesioni', [AdesioniController::class, 'index'])->name('adesioni.index');
    Route::resource('adesioni', AdesioniController::class);
    Route::get('adesioni/create', [AdesioniController::class, 'create'])->name('adesioni.create');
    Route::get('eventi', [EventiController::class, 'index'])->name('eventi.index');
    Route::resource('eventi', EventiController::class);
});



require __DIR__.'/auth.php';
