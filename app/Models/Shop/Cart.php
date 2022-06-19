<?php

namespace App\Models\Shop;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // pause

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // pause
    /*
    public function products()
    {
        return $this->belongsToMany(Product::class)->as('cart_items');
    }
    */





    public function getTotal(){
        //$cart_items = $this->cartItems;
        $cart_items = CartItem::with('attributeValues')->where('cart_id',$this->id)->get();
        $cart_total = 0 ;
        foreach($cart_items as $item){
            if($item->product->hasDiscount()){
                $discount_type = $item->product->discount->type;
                if($discount_type == 'percent'){
                            $discount = $item->product->price * $item->product->discount->value / 100;
                            if($item->product->unit == 'gram')
                                $cart_total = $cart_total +  (($item->product->price - $discount) * $item->quantity / 1000);
                            else
                                $cart_total = $cart_total +  ($item->product->price - $discount) * $item->quantity;
                            }
                else{
                            if($item->product->unit == 'gram')
                                $cart_total = $cart_total +  (($item->product->price - $item->product->discount->value) * $item->quantity / 1000);
                            else
                                $cart_total = $cart_total +  ($item->product->price - $item->product->discount->value) * $item->quantity;
                }
            }
            else  // no discount for this item
                if($item->product->unit == 'gram')
                        $cart_total = $cart_total + ($item->product->price * $item->quantity / 1000);
                else
                        $cart_total = $cart_total + ($item->product->price * $item->quantity);
            // add attr_val_prices
            foreach($item->attributeValues as $attr_val)
                $cart_total += $attr_val->price;
        }
        // points discount
        $points_applied = Session::get('points_applied');
        if($points_applied){
            $one_percent_discount_by_points = (float) Setting::where('key','one_percent_discount_by_points')->first()->value;
            $discount_percent = $points_applied / $one_percent_discount_by_points ;
            $discount = $discount_percent * $cart_total / 100 ;
            $cart_total -= $discount;
            // save data in session to output it
            Session::put('discount_percent',$discount_percent);
            Session::put('discount',$discount);
            Session::put('total_before_discount',$cart_total + $discount);
        }
        return $cart_total ;
    }

    public function getTotalSubmittingOrder(&$total_order_price,&$order_items_arr , &$tax_value){   // argument by reference
        //$cart_items = $this->cartItems;
        $cart_items = CartItem::with('attributeValues')->where('cart_id',$this->id)->get();
        $total_order_price = 0 ;
        $tax_row = Setting::where('key','tax')->first();
        $tax = (float) $tax_row->value;
        foreach($cart_items as $item){
            if($item->product->hasDiscount()){
                $discount_type = $item->product->discount->type;
                if($discount_type == 'percent'){
                     $discount = $item->product->price * $item->product->discount->value / 100;
                     $new_price = $item->product->price - $discount;
                     if($item->product->unit == 'gram')
                            $total_order_price += $new_price * $item->quantity / 1000 ;
                     else
                            $total_order_price += $new_price * $item->quantity;
                     // order item
                     // attribute add to order_item
                     // $attribute_values is array of arrays
                     if($item->attributeValues()->count() > 0)
                        foreach($item->attributeValues as $attr_val){
                                $attribute_values[] = array('id' => $attr_val->id , 'attribute_id' => $attr_val->attribute_id,
                                                            'value' => $attr_val->value , 'price' => $attr_val->price);
                        }
                     else // no attribute values
                        $attribute_values = array(); // empty
                     $attribute_values = serialize($attribute_values);
                     $order_items_arr[] = ['product_id' => $item->product->id , 'price' => $new_price , 'discount' => $discount , 'quantity' => $item->quantity,
                                            'attribute_values' => $attribute_values];
                     }
                else {
                     $new_price = $item->product->price - $item->product->discount->value;
                     if($item->product->unit == 'gram')
                            $total_order_price += $new_price * $item->quantity / 1000 ;
                     else
                            $total_order_price += $new_price * $item->quantity;
                     // order item
                     // attribute add to order_item
                     // $attribute_values is array of arrays
                     if($item->attributeValues()->count() > 0)
                     foreach($item->attributeValues as $attr_val){
                             $attribute_values[] = array('id' => $attr_val->id , 'attribute_id' => $attr_val->attribute_id,
                                                         'value' => $attr_val->value , 'price' => $attr_val->price);
                     }
                     else // no attribute values
                        $attribute_values = array(); // empty
                     $attribute_values = serialize($attribute_values);
                     $order_items_arr[] = ['product_id' => $item->product->id , 'price' => $new_price , 'discount' => $item->product->discount->value  , 'quantity' => $item->quantity,
                                           'attribute_values' => $attribute_values];
                }
            }
            else{   // no discount
                     if($item->product->unit == 'gram')
                        $total_order_price += $item->product->price * $item->quantity / 1000  ;
                     else
                        $total_order_price += $item->product->price * $item->quantity;
                      // order item
                     // attribute add to order_item
                     // $attribute_values is array of arrays
                     if($item->attributeValues()->count() > 0)
                        foreach($item->attributeValues as $attr_val){
                                $attribute_values[] = array('id' => $attr_val->id , 'attribute_id' => $attr_val->attribute_id,
                                                            'value' => $attr_val->value , 'price' => $attr_val->price);
                        }
                     else // no attribute values
                        $attribute_values = array(); // empty
                     $attribute_values = serialize($attribute_values);
                     $order_items_arr[] = ['product_id' => $item->product->id , 'price' => $item->product->price , 'discount' => 0  , 'quantity' => $item->quantity,
                                           'attribute_values' => $attribute_values];
            }
            // add attr_val_prices
            foreach($item->attributeValues as $attr_val)
                $total_order_price += $attr_val->price;
            unset($attribute_values); // delete previuos attrs
        }
        // check if points applied so we make discount
        $points_applied = Session::get('points_applied');
        if($points_applied){
            $one_percent_discount_by_points = (float) Setting::where('key','one_percent_discount_by_points')->first()->value;
            $discount_percent = $points_applied / $one_percent_discount_by_points ;
            $discount = $discount_percent * $total_order_price / 100 ;
            $total_order_price -= $discount;
        }
        $tax_value = $tax * $total_order_price / 100 ;
        $grand_order_total = $total_order_price + $tax_value ;
        return $grand_order_total;
    }

    public function calculateDeliverTime($order_submit = false){
        $close_delivery = Setting::where('key','close_delivery')->first()->value;
        $hours_deliver_when_free = (float) Setting::where('key','hours_deliver_when_free')->first()->value;
        $number_of_orders_increase_time = (float) Setting::where('key','number_of_orders_increase_time')->first()->value;
        //$time_line =  Carbon::create(now()->year,now()->month,now()->day,19,00);    // 1 pm is the line for delivery time change for next day
        $H = substr($close_delivery, 0, 2);
        $M = substr($close_delivery, 3, 2);
        $time_line =  Carbon::create(now()->year,now()->month,now()->day,$H,$M);    // the line for delivery time change for next day
        if(now() < $time_line){   // delivery today
            // calculate the number of orders not delivered
            $busy_orders_count = Order::where(function($query){
                $query->where('status','pending')
                ->orWhere('status','preparing');
            })->count();
            // if we are free we take X hours to deliver the order for example
            // we increase one hour for every Y orders for example
           if($order_submit == false){
                return  ceil($busy_orders_count * 1 / $number_of_orders_increase_time) + $hours_deliver_when_free ;  // round hours to the upper number
           }
           else{
                $hours_remaining_to_deliver = ceil($busy_orders_count * 1 / $number_of_orders_increase_time) + $hours_deliver_when_free ;  // round hours to the upper number
                return   Carbon::create(now()->year,now()->month,now()->day,now()->hour + $hours_remaining_to_deliver,now()->minute,now()->second);
           }
        }
        else{    // deliver tomorrow
            if($order_submit == false){
                 return 'order now and we will deliver it to you tomorrow';
            }
            else{
                return  Carbon::create(now()->year,now()->month,now()->day + 1,now()->hour,now()->minute,now()->second);
            }
        }
    }
}
