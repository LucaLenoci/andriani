<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdesioniController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('adesioni', [AdesioniController::class, 'index'])->name('adesioni.index');
Route::resource('adesioni', AdesioniController::class);

