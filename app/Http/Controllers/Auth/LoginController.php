<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Shop\CartItem;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
    //protected $redirectTo = '/';
    public function redirectTo() {
        $user = User::find(Auth::user()->id);
        if($user->hasRole(['super_admin']))
                    return '/dashboard';
        if($user->hasRole(['customer']))
                    return '/';
      }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            // add cart session details to user logged in cart
            $session_cart = Session::get('cart');
            if($session_cart){    // there is cart in the session
                $user_loggedin = User::with('cart.cartItems')->find(Auth::user()->id);
                // delete old cart items if exist
                if($user_loggedin->cart->cartItems->count()>0)
                    foreach($user_loggedin->cart->cartItems as $old_item){
                        $old_item->delete();
                    }
                // add session cart to user cart
                foreach($session_cart as $item){
                        $cartItem = CartItem::create([
                            'cart_id' => $user_loggedin->cart->id,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                        ]);
                        // add attributes to cart item
                        $attributes = $item['attributes'];
                        if(is_array($attributes) && $attributes)  // there is attributes to add
                            for ($i = 0 ; $i < sizeof($attributes) ; $i++) {
                                $attribute_value_id = $attributes[$i]['id'];  // attribute_value id
                                $cartItem->attributeValues()->attach($attribute_value_id);
                            }
                }
            }
            Session::forget('cart');

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


}
