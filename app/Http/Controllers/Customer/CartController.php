<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\Setting;
use App\Models\Shop\Cart;
use App\Models\Shop\CartItem;
use App\Models\Shop\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    //
    public function viewCart(){
        $user = User::findOrFail(Auth::user()->id);
        $points = $user->maxAppliedPoints();
        $cart = $user->cart;
        //$cart_items = $cart->cartItems;
        $cart_items = CartItem::with('attributeValues')->where('cart_id',$cart->id)->get();
        $tax_row = Setting::where('key','tax')->first();
        $min_order_row = Setting::where('key','min_order_limit')->first();
        $one_percent_discount = Setting::where('key','one_percent_discount_by_points')->first()->value;
        $tax = (float) $tax_row->value;
        $min_order = (float) $min_order_row->value;
        $is_accept_orders = Setting::isAcceptOrders();
        $cart_total = $cart->getTotal();
        $tax_value = $tax * $cart_total / 100 ;
        $cart_grand_total = $cart_total + $tax_value ;
        $cart_grand_total = number_format((float)$cart_grand_total, 2, '.', '');
        return view('Customer.cart.view_details',['cart'=>$cart,'cart_items'=>$cart_items,'cart_total' => $cart_total,'tax'=>$tax,
                                                  'cart_grand_total' => $cart_grand_total ,
                                                  'min_order' => $min_order,'points'=>$points,'one_percent_discount'=>$one_percent_discount,
                                                  'isAcceptOrders' => $is_accept_orders,
                                                    ]);
    }


    public function viewGuestCart(){
        $guest = User::whereHas('roles', function($q){
            $q->where('name', 'guest');
        })->first();
        $cart = Session::get('cart');
        $cart_items = collect();
        $tax_row = Setting::where('key','tax')->first();
        $min_order_row = Setting::where('key','min_order_limit')->first();
        $tax = (float) $tax_row->value;
        $min_order = (float) $min_order_row->value;
        $is_accept_orders = Setting::isAcceptOrders();
        $cart_total = 0 ; // sub total
        $cart_items = collect();
        if($cart)
            $cart_grand_total =  $guest->getGuestTotalCart($cart , $cart_items , $tax , $cart_total);
        else
            $cart_grand_total = 0 ;
        return view('Guest.cart.view_details',['cart'=>$cart,'cart_items'=>$cart_items,'cart_total' => $cart_total,'tax'=>$tax,
                                                  'cart_grand_total' => $cart_grand_total ,  'min_order'=>$min_order,
                                                  'isAcceptOrders' => $is_accept_orders]);
    }

    public function deleteCartItem($id){
        $cart_item = CartItem::findOrFail($id);
        foreach($cart_item->attributeValues as $attr_val)
            $cart_item->attributeValues()->detach($attr_val->id);
        $cart_item->delete();
        return back();
    }

    // guest
    public function deleteGuestCartItem($index){
        $cart = Session::get('cart');
        array_splice($cart, $index, 1);
        Session::put('cart',$cart);
        return back();
    }

    public function deleteCartContent($id = 'session_cart'){   // default value because its a optional parameter
        if($id != 'session_cart'){
            $cart = Cart::findOrFail($id);
            //$cart->cartItems()->delete();
            $cart_items = $cart->cartItems;
            foreach($cart_items as $item)
                foreach($item->attributeValues as $attr_val)
                    $item->attributeValues()->detach($attr_val->id);
            $cart->cartItems()->delete();
            return back();
        }
        else{    // Cart Guest Session
            Session::forget('cart');
            return back();
        }
    }
}
