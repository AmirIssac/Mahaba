<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = User::find(Auth::user()->id);
                //return redirect(RouteServiceProvider::HOME);
                if($user->hasRole(['super_admin']))
                    return redirect(route('dashboard'));
                if($user->hasRole(['admin']))
                    return redirect(route('dashboard'));
                if($user->hasRole(['employee']))
                    return redirect(route('employee.orders'));
                elseif($user->hasRole(['customer']))
                    return redirect(route('index'));
            }
        }

        return $next($request);
    }
}
