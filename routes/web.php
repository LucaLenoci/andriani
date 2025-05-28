<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdesioniController;
use App\Http\Controllers\EventiController;
use App\Http\Controllers\PuntiVenditaController;
use App\Http\Controllers\Api\DatiPerMop;
use App\Http\Controllers\MaterialiController;
use App\Http\Controllers\RientroDatiController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    Route::group(['middleware' => function ($request, $next) {
        if (!str_starts_with($request->path(), 'dashboard')) {
            Log::info('Azione utente generica', [
                'user_id' => Auth::id(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'input' => $request->except(['password', 'password_confirmation']),
            ]);
        }
        return $next($request);
    }], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('verified')->name('dashboard');
        Route::get('adesioni', [AdesioniController::class, 'index'])->name('adesioni.index');
        Route::resource('adesioni', AdesioniController::class);
        Route::get('adesioni/create', [AdesioniController::class, 'create'])->name('adesioni.create');
        Route::get('eventi', [EventiController::class, 'index'])->name('eventi.index');
        Route::resource('eventi', EventiController::class);
        Route::get('/punti-vendita/search', [PuntiVenditaController::class, 'search'])->name('punti-vendita.search');
        Route::get('/materiali/search', [MaterialiController::class, 'search'])->name('materiali.search');
        Route::get('/dati-per-mop', [DatiPerMop::class, 'adesioni'])->name('dati-per-mop.adesioni');
        Route::get('/rientro-dati/{id}', [RientroDatiController::class, 'show'])->name('rientro-dati.show');
        Route::get('/punti-vendita', [PuntiVenditaController::class, 'index'])->name('punti-vendita.index');
        Route::get('/materiali', [MaterialiController::class, 'index'])->name('materiali.index');
        Route::get('/aree-di-competenza', [App\Http\Controllers\AreeDiCompetenzaController::class, 'index'])->name('aree-di-competenza.index');
    });


    Route::get('/dati-per-mop/{tipo}', function (Request $request, $tipo) {
        $token = $request->header('API-TOKEN');

        if ($token !== env('API_TOKEN')) {
            return response()->json(['error' => 'Token non valido'], 403);
        }

        return app(DatiPerMop::class)->perTipo($request, $tipo);
    });

    // SOC con admin check + log
    Route::group(['middleware' => function ($request, $next) {
        if (!Auth::check() || Auth::id() !== 1) {
            abort(403, 'Accesso non autorizzato - solo admin');
        }
        Log::info('Azione SOC admin', [
            'user_id' => Auth::id(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'input' => $request->except(['password', 'password_confirmation']),
        ]);
        return $next($request);
    }], function () {
        Route::get('/dashboard/logs', [App\Http\Controllers\SocDashboardController::class, 'index'])->name('dashboard.logs');
        Route::get('/dashboard/statistiche', [App\Http\Controllers\SocDashboardController::class, 'statistiche'])->name('dashboard.statistiche');
        Route::get('/dashboard/errori', [App\Http\Controllers\SocDashboardController::class, 'errori'])->name('dashboard.errori');
    });

});

require __DIR__.'/auth.php';
