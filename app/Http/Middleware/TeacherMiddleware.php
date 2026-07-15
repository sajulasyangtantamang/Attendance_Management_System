<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! ($request->user()->isTeacher() || $request->user()->isAdmin())) {
            abort(403, 'You are not authorized to access this area.');
        }

        return $next($request);
    }
}
