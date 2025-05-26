<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdesioniController;
use App\Http\Controllers\EventiController;
use App\Http\Controllers\PuntiVenditaController;
use App\Http\Controllers\Api\DatiPerMop;

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
    Route::get('/punti-vendita/search', [PuntiVenditaController::class, 'search'])->name('punti-vendita.search');
    Route::get('/materiali/search', [MaterialiController::class, 'search'])->name('materiali.search');
    Route::get('/dati-per-mop', [DatiPerMop::class, 'adesioni'])->name('dati-per-mop.adesioni');
});

Route::get('/dati-per-mop/{tipo}', [DatiPerMop::class, 'perTipo'])->name('dati-per-mop.tipo');


require __DIR__.'/auth.php';
