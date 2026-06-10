<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Single-user session guard (ports auth.php::require_auth()).
class VaultAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('vault_auth')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
