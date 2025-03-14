<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtistMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'artist') {
            return $next($request);
        }
        return redirect('/artist-login')->with('error', 'Access Denied! You are not an Artist.');
    }
}
