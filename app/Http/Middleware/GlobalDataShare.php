<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GlobalDataShare
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
        $contact_phone = Setting::where('key','contact_phone')->first()->value;
        $contact_email = Setting::where('key','contact_email')->first()->value;
        if(Auth::user()){
            $user = User::find(Auth::user()->id);
            $point = $user->profile->points;
            $cart = $user->cart;
            $cart_items = $cart->cartItems;
            // sharing data with all views
            View::share(['user' => $user, 'cart' => $cart , 'cart_items' => $cart_items,'point'=>$point,
            'contact_phone'=>$contact_phone,'contact_email'=>$contact_email]);
        }
        View::share(['contact_phone'=>$contact_phone,
                     'contact_email'=>$contact_email,]);
        return $next($request);
    }
}
