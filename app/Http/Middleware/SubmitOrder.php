<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SubmitOrder
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
        if(Auth::user()) {
            $user = User::find(Auth::user()->id);
            $cart = $user->cart;
            $cart_items = $cart->cartItems;
            $total_order_price = 0 ;
            $min_order_limit_row = Setting::where('key', 'min_order_limit')->first();
            $min_order_limit = (float) $min_order_limit_row->value;
            $tax_row = Setting::where('key', 'tax')->first();
            $tax = (float) $tax_row->value;
            // $order_items_arr is array of arrays
            $order_items_arr = array();
            $total_order_price = 0 ;
            $tax_value = 0 ;
            $grand_order_total = $cart->getTotalSubmittingOrder($total_order_price, $order_items_arr, $tax_value);
            if ($grand_order_total < $min_order_limit || !Setting::isAcceptOrders()) {
                return redirect(route('view.cart'));
            }
            return $next($request);
        }
        else{  // Guest
            $guest = User::whereHas('roles', function($q){
                $q->where('name', 'guest');
            })->first();
            $cart = Session::get('cart');
            if($cart){
                $cart_items = collect();
                $tax_row = Setting::where('key','tax')->first();
                $min_order_row = Setting::where('key','min_order_limit')->first();
                $tax = (float) $tax_row->value;
                $min_order_limit = (float) $min_order_row->value;
                $cart_total = 0 ; // sub total
                $cart_grand_total =  $guest->getGuestTotalCart($cart , $cart_items , $tax , $cart_total);
                if ($cart_grand_total < $min_order_limit || !Setting::isAcceptOrders()) {
                    abort(403);
                }
            }
            else{ // no cart data
                abort(403);
            }
            return $next($request);
        }
    }
}
