<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureMemberCheck
{

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return $next($request);
        }

        $allowedRoutes = [
            // 'member.setup',
            'member.store-profile',
            'member.activation',
            'member.store-payment',
            'filament.admin.auth.logout',
            'logout'
        ];

        if (in_array($request->route()->getName(), $allowedRoutes)) {
            return $next($request);
        }


        // if (! $user->member) {
        //     return redirect()->route('member.setup');
        // }

        if ($user->member->status !== 'active') {
            return redirect()->route('member.activation');
        }

        return $next($request);
    }
}
