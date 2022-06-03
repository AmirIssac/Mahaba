<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $order_id = $request->route('order_id');
        if(Auth::user()){
            $check_related = User::whereHas('orders', function($q) use ($order_id) {
                $q->where('id', $order_id);
            })->where('id',Auth::user()->id)->count();
            if($check_related < 1)  // the order is not for this customer
                return redirect('/');
            return $next($request);
        }
        return redirect('/');
    }
}
