<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class LogUserActions
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $user = $request->user();
        $userId = $user ? $user->id : 'guest';

        $logData = [
            'user_id' => $userId,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'agent' => $request->userAgent(),
            'input' => $request->except(['password', 'password_confirmation']), // evita password
        ];

        Log::info('User action:', $logData);

        return $response;
    }
}
