<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Shop\Cart;
use App\Models\Shop\CartItem;
use App\Models\Shop\Favorite;
use App\Models\Shop\Profile;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string'],//, 'min:8', 'confirmed'],
            'phone' => 'required|digits:9|regex:/(5)[0-9]{8}/',
            'first_name' => 'required',
            'last_name' => 'required' ,
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

    protected function create(array $data)
    {
        /*
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        */
        $user = User::create([
            'name' => $data['first_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $phone = '00971'.$data['phone'];
        Profile::create([
            'user_id' => $user->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $phone,
            'address_address' => null,
            'address_street' => null,
            'address_building_apartment' => null,
            'address_latitude' => null,
            'address_longitude' => null,
        ]);
        $cart = Cart::create([
            'user_id' => $user->id,
        ]);
        $favorite = Favorite::create([
            'user_id' => $user->id,
        ]);
        $user->assignRole('customer');
        // add session cart to DB
        $session_cart = Session::get('cart');
        if($session_cart){    // there is cart in the session
            foreach($session_cart as $item){
                    $cartItem = CartItem::create([
                        'cart_id' => $cart->id,
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
        //$user->assignRole('customer');
        return $user;
    }

    /*
    protected function create(Request $request)
    {
        $user = User::create([
            'name' => $request->first_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Profile::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address_address' => null,
            'address_latitude' => null,
            'address_longitude' => null,
        ]);
        $cart = Cart::create([
            'user_id' => $user->id,
        ]);
        $favorite = Favorite::create([
            'user_id' => $user->id,
        ]);
        $user->assignRole('customer');
        // add session cart to DB
        $session_cart = Session::get('cart');
        if($session_cart){    // there is cart in the session
            foreach($session_cart as $item){
                    $cartItem = CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                    ]);
            }
        }
        Session::forget('cart');
        //$user->assignRole('customer');
        return $user;
    }
    */
}
