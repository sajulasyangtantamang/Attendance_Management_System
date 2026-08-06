<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    /**
     * Routes a user with a pending forced password change is still allowed to hit,
     * so they can actually reach the form that lets them change it (and log out).
     */
    protected array $exemptRouteNames = [
        'profile.edit',
        'profile.update',
        'profile.password',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_change_password && ! $request->routeIs($this->exemptRouteNames)) {
            return redirect()->route('profile.edit')
                ->with('warning', 'You must change your temporary password before you can continue.');
        }

        return $next($request);
    }
}
