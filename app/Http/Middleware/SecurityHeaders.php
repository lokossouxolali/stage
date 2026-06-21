<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $contentType = $response->headers->get('Content-Type');
        $lowerContentType = strtolower((string) $contentType);
        $shouldForceUtf8 = str_starts_with($lowerContentType, 'text/')
            || str_starts_with($lowerContentType, 'application/json')
            || str_starts_with($lowerContentType, 'application/javascript');

        if ($contentType && $shouldForceUtf8 && !str_contains($lowerContentType, 'charset=')) {
            $response->headers->set('Content-Type', $contentType . '; charset=UTF-8');
        }

        if ($contentType === null && $response->getContent() !== false) {
            $response->headers->set('Content-Type', 'text/html; charset=UTF-8');
        }

        // Headers de sécurité
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // Content Security Policy
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
               "style-src 'self' 'unsafe-inline'; " .
               "img-src 'self' data: https:; " .
               "font-src 'self' data:; " .
               "connect-src 'self'; " .
               "frame-ancestors 'none';";
        
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
