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
        $cart_total = $cart->getTotal();
        $tax_value = $tax * $cart_total / 100 ;
        $cart_grand_total = $cart_total + $tax_value ;
        $cart_grand_total = number_format((float)$cart_grand_total, 2, '.', '');
        return view('Customer.cart.view_details',['cart'=>$cart,'cart_items'=>$cart_items,'cart_total' => $cart_total,'tax'=>$tax,
                                                  'cart_grand_total' => $cart_grand_total ,
                                                  'min_order' => $min_order,'points'=>$points,'one_percent_discount'=>$one_percent_discount
                                                    ]);
    }

    /*
    public function viewGuestCart(){
        $cart = Session::get('cart');
        $cart_items = collect();
        $tax_row = Setting::where('key','tax')->first();
        $min_order_row = Setting::where('key','min_order_limit')->first();
        $tax = (float) $tax_row->value;
        $min_order = (float) $min_order_row->value;
        $cart_total = 0 ;
        if($cart){
        foreach($cart as $c_item){
            $item = Product::find($c_item['product_id']);  // but we have to take quantity too (its not stored in product object its stored in cart_item table and we dont have cart_item in session process)
            $item->quantity = $c_item['quantity'];
            $cart_items->add($item);
        }
        foreach($cart_items as $item){
            if($item->hasDiscount()){
                if($item->isPercentDiscount()){
                            $discount = $item->price * $item->discount->value / 100;
                            if($item->unit == 'gram')
                                $cart_total = $cart_total +  (($item->price - $discount) * $item->quantity / 1000);
                            else
                                $cart_total = $cart_total +  ($item->price - $discount) * $item->quantity;
                            }
                else{
                            if($item->unit == 'gram')
                                $cart_total = $cart_total +  (($item->price - $item->discount->value) * $item->quantity / 1000);
                            else
                                $cart_total = $cart_total +  ($item->price - $item->discount->value) * $item->quantity;
                }
            }
            else  // no discount for this item
                if($item->unit == 'gram')
                        $cart_total = $cart_total + ($item->price * $item->quantity / 1000);
                else
                        $cart_total = $cart_total + ($item->price * $item->quantity);
        }
        }
        return view('Guest.cart.view_details',['cart'=>$cart,'cart_items'=>$cart_items,'cart_total' => $cart_total,'tax'=>$tax,
                                                    'min_order'=>$min_order]);
    }
    */

    public function viewGuestCart(){
        $cart = Session::get('cart');
        $cart_items = collect();
        $tax_row = Setting::where('key','tax')->first();
        $min_order_row = Setting::where('key','min_order_limit')->first();
        $tax = (float) $tax_row->value;
        $min_order = (float) $min_order_row->value;
        $cart_total = 0 ;
        if($cart){
        foreach($cart as $c_item){
            $item = Product::find($c_item['product_id']);  // but we have to take quantity too (its not stored in product object its stored in cart_item table and we dont have cart_item in session process)
            $item->quantity = $c_item['quantity'];
            $item->attributes = $c_item['attributes'];
            $cart_items->add($item);
        }
        foreach($cart_items as $item){
            if($item->hasDiscount()){
                if($item->isPercentDiscount()){
                            $discount = $item->price * $item->discount->value / 100;
                            if($item->isGram())
                                $cart_total = $cart_total +  (($item->price - $discount) * $item->quantity / 1000);
                            else
                                $cart_total = $cart_total +  ($item->price - $discount) * $item->quantity;
                            }
                else{
                            if($item->isGram())
                                $cart_total = $cart_total +  (($item->price - $item->discount->value) * $item->quantity / 1000);
                            else
                                $cart_total = $cart_total +  ($item->price - $item->discount->value) * $item->quantity;
                }
            }
            else  // no discount for this item
                if($item->isGram())
                        $cart_total = $cart_total + ($item->price * $item->quantity / 1000);
                else
                        $cart_total = $cart_total + ($item->price * $item->quantity);
            // add attr_vals costs
            foreach ($item->attributes as $attr_val) {
                $attr_val_obj = AttributeValue::find($attr_val['id']);
                if ($attr_val_obj->isValue()) {
                    $cart_total+=$attr_val_obj->printAttributeValuePrice($item->id);
                } elseif ($attr_val_obj->isPercent()) {
                    $cart_total+=$attr_val_obj->printAttributeValuePrice($item->id) * $item->quantity;
                }
            }
        }
        }
        $tax_value = $tax * $cart_total / 100 ;
        $cart_grand_total = $cart_total + $tax_value ;
        $cart_grand_total = number_format((float)$cart_grand_total, 2, '.', '');
        return view('Guest.cart.view_details',['cart'=>$cart,'cart_items'=>$cart_items,'cart_total' => $cart_total,'tax'=>$tax,
                                                  'cart_grand_total' => $cart_grand_total ,  'min_order'=>$min_order]);
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
