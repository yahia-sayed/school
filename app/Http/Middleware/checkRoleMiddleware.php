<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class checkRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param string $role
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {

        $roles = [
            'admin' => ['admin'],
            'supervisor' => ['admin', 'supervisor'],
            'teacher' => ['admin', 'supervisor', 'teacher'],
            'student' => ['admin', 'supervisor', 'student']
        ];

        if (!in_array(auth()->user()->role, $roles[$role])) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
