<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChauffeurMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->status === 'chauffeur') {
            return $next($request);
        }
        
        return redirect('/')->with('error', 'Accès non autorisé.');
    }
} 