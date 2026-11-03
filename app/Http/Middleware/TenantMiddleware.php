<?php

namespace App\Http\Middleware;

use App\Services\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // If user belongs to a tenant, initialize context
            if ($user->tenant) {
                app(TenantContext::class)->setTenant($user->tenant);
            }
        }

        return $next($request);
    }
}
