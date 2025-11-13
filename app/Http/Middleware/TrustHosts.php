<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrustHosts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Permitir todos los hosts en desarrollo local
        if (app()->environment('local')) {
            return $next($request);
        }

        // En producción, validar el host
        $allowedHosts = [
            config('app.url'),
            '*.ondigitalocean.app',
        ];

        $host = $request->getHost();
        
        foreach ($allowedHosts as $pattern) {
            if ($this->matchesPattern($pattern, $host)) {
                return $next($request);
            }
        }

        abort(403, 'Host no permitido');
    }

    /**
     * Determine if the host matches the pattern.
     */
    protected function matchesPattern(string $pattern, string $host): bool
    {
        $pattern = str_replace(['http://', 'https://', '*'], ['', '', '.*'], $pattern);
        return (bool) preg_match('#^' . $pattern . '$#', $host);
    }
}
