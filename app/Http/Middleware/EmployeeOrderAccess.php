<?php

namespace App\Http\Middleware;

use App\Models\Shop\Order;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeOrderAccess
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
            $store_id = Order::find($order_id)->store->id;
            $check_related = User::whereHas('stores', function($q) use ($store_id) {
                $q->where('stores.id', $store_id);
            })->where('users.id',Auth::user()->id)->count();
            if($check_related < 1)  // the order is not in the store that this employee work
                abort(403);
            return $next($request);
        }
        abort(403);
    }
}
