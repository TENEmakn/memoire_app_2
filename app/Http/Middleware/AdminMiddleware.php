<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->status === 'admin' || Auth::user()->status === 'gestionnaire')) {
            return $next($request);
        }
        
        return redirect('/')->with('error', 'Accès non autorisé.');
    }
} 