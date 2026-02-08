<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()->role !== 'user') {
            abort(403, 'Akses khusus petugas arsip');
        }

        return $next($request);
    }
}
