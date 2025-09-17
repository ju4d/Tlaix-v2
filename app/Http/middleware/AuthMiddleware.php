<?php
// app/Http/Middleware/AuthMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware {
    public function handle(Request $request, Closure $next, $role = null) {
        // Check if user is logged in
        if (!session('user_id')) {
            return redirect('/login')->withErrors(['auth' => 'Please log in to continue.']);
        }

        // Check role if specified
        if ($role && session('user_role') !== $role) {
            return redirect('/dashboard')->withErrors(['role' => 'Access denied for your role.']);
        }

        return $next($request);
    }
}
