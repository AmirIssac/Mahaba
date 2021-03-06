<?php

namespace App\Models;

use App\Models\Shop\Cart;
use App\Models\Shop\Order;
use App\Models\Shop\PaymentDetail;
use App\Models\Shop\Profile;
use App\Models\Shop\Transaction;
use App\Models\Shop\Favorite;
use App\Models\Shop\Product;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable , HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // relationships
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function favorite()
    {
        return $this->hasOne(Favorite::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderSystems()
    {
        return $this->hasMany(OrderSystem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function paymentsDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }

    public function discountDetails()
    {
        return $this->hasMany(DiscountDetail::class);
    }

    public function stores(){
        return $this->belongsToMany(Store::class, 'store_user');
    }

    public function productsRate()
    {
        return $this->hasMany(ProductRate::class);
    }


    // custom functions

    public function adminstrative(){    //  ???????? ???? ????????
        $user = User::find(Auth::user()->id);
        if($user->hasRole('super_admin') || $user->hasRole('admin') || $user->hasRole('employee'))
            return true;
        else
            return false;
    }

    public function isSuperAdmin($user){
        if($user->hasRole('super_admin'))
            return true;
        else
            return false;
    }

    public function isAdmin($user){
        if($user->hasRole('admin'))
            return true;
        else
            return false;
    }
    public function isEmployee($user){
        if($user->hasRole('employee'))
            return true;
        else
            return false;
    }
    public function isCustomer($user){
        if($user->hasRole('customer'))
            return true;
        else
            return false;
    }
    public function isGuest(){
        if($this->hasRole('guest'))
            return true;
        else
            return false;
    }

    public function calculateGuestDeliverTime($order_submit = false){
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


    public function getGuestTotalCart($cart , &$cart_items , $tax , &$total_order_price){
        $total_order_price = 0 ;
        foreach($cart as $c_item){
            $item = Product::find($c_item['product_id']);  // but we have to take quantity too (its not stored in product object its stored in cart_item table and we dont have cart_item in session process)
            $item->quantity = $c_item['quantity'];
            $item->at = $c_item['attributes'];
            $cart_items->add($item);
        }
        foreach($cart_items as $item){
            if($item->hasDiscount()){
                if($item->isPercentDiscount()){
                            $discount = $item->price * $item->discount->value / 100;
                            if($item->isGram())
                                $total_order_price = $total_order_price +  (($item->price - $discount) * $item->quantity / 1000);
                            else
                                $total_order_price = $total_order_price +  ($item->price - $discount) * $item->quantity;
                            }
                else{
                            if($item->isGram())
                                $total_order_price = $total_order_price +  (($item->price - $item->discount->value) * $item->quantity / 1000);
                            else
                                $total_order_price = $total_order_price +  ($item->price - $item->discount->value) * $item->quantity;
                }
            }
            else  // no discount for this item
                if($item->isGram())
                        $total_order_price = $total_order_price + ($item->price * $item->quantity / 1000);
                else
                        $total_order_price = $total_order_price + ($item->price * $item->quantity);
            // add attr_vals costs
            if($item->at)
                foreach ($item->at as $attr_val) {
                    $attr_val_obj = AttributeValue::find($attr_val['id']);
                    if ($attr_val_obj->isValue()) {
                        $total_order_price+=$attr_val_obj->printAttributeValuePrice($item->id);
                    } elseif ($attr_val_obj->isPercent()) {
                        $total_order_price+=$attr_val_obj->printAttributeValuePrice($item->id) * $item->quantity;
                    }
                }
        }
        $tax_value = $tax * $total_order_price / 100 ;
        $cart_grand_total = $total_order_price + $tax_value ;
        $cart_grand_total = number_format((float)$cart_grand_total, 2, '.', '');
        return $cart_grand_total;
    }

    // WE NEED HERE ORDER ITEMS ARRAY TO STORE IT IN ORDERITEMS (array of arrays)
    public function getGuestTotalOrderOnSubmit($cart , &$cart_items , $tax , &$tax_value , &$total_order_price , &$order_items_arr){
        $total_order_price = 0 ;
        foreach($cart as $c_item){
            $item = Product::find($c_item['product_id']);  // but we have to take quantity too (its not stored in product object its stored in cart_item table and we dont have cart_item in session process)
            $item->quantity = $c_item['quantity'];
            $item->at = $c_item['attributes'];
            $cart_items->add($item);
        }
        foreach($cart_items as $item){
            if($item->hasDiscount()){
                if($item->isPercentDiscount()){
                    $discount = $item->price * $item->discount->value / 100;
                    $new_price = $item->price - $discount;
                    if($item->isGram())
                            $total_order_price += $new_price * $item->quantity / 1000 ;
                    else
                            $total_order_price += $new_price * $item->quantity;
                    // order item
                    // attribute add to order_item
                    // $attribute_values is array of arrays
                    if(count($item->at)>0)
                        foreach($item->at as $attr_val){
                            $attribute_values[] = array('id' => $attr_val['id'] , 'attribute_id' => $attr_val['attribute_id'],
                                                        'value' => $attr_val['value'] , 'value_en' => $attr_val['value_en'] , 'value_type' =>  $attr_val['value_type'] ,
                                                        'price' => $attr_val['price']);
                        }
                    else // no attribute values
                        $attribute_values = array(); // empty
                    $attribute_values = serialize($attribute_values);
                    $order_items_arr[] = ['product_id' => $item->id , 'price' => $new_price , 'discount' => $discount , 'quantity' => $item->quantity, 'attr_vals' => $attribute_values];
                    }
                else {
                    $new_price = $item->price - $item->discount->value;
                    if($item->isGram())
                            $total_order_price += $new_price * $item->quantity / 1000 ;
                    else
                            $total_order_price += $new_price * $item->quantity;

                    // order item

                    // attribute add to order_item
                    // $attribute_values is array of arrays
                    if(count($item->at)>0)
                        foreach($item->at as $attr_val){
                            $attribute_values[] = array('id' => $attr_val['id'] , 'attribute_id' => $attr_val['attribute_id'],
                                                        'value' => $attr_val['value'] , 'value_en' => $attr_val['value_en'] , 'value_type' =>  $attr_val['value_type'] ,
                                                        'price' => $attr_val['price']);
                        }
                    else // no attribute values
                        $attribute_values = array(); // empty
                    $attribute_values = serialize($attribute_values);
                    $order_items_arr[] = ['product_id' => $item->id , 'price' => $new_price , 'discount' => $item->discount->value  , 'quantity' => $item->quantity, 'attr_vals' => $attribute_values];
                }
            }
            else{   // no discount
                    if($item->isGram())
                        $total_order_price += $item->price * $item->quantity / 1000  ;
                    else
                        $total_order_price += $item->price * $item->quantity;
                    // order item

                    // attribute add to order_item
                    // $attribute_values is array of arrays
                    if(count($item->at)>0)
                        foreach($item->at as $attr_val){
                            $attribute_values[] = array('id' => $attr_val['id'] , 'attribute_id' => $attr_val['attribute_id'],
                                                        'value' => $attr_val['value'] , 'value_en' => $attr_val['value_en'] , 'value_type' =>  $attr_val['value_type'] ,
                                                        'price' => $attr_val['price']);
                        }
                    else // no attribute values
                        $attribute_values = array(); // empty
                    $attribute_values = serialize($attribute_values);

                    $order_items_arr[] = ['product_id' => $item->id , 'price' => $item->price , 'discount' => 0  , 'quantity' => $item->quantity , 'attr_vals' => $attribute_values];
            }
            // add attr_vals costs
            foreach ($item->at as $attr_val) {
                $attr_val_obj = AttributeValue::find($attr_val['id']);
                if ($attr_val_obj->isValue()) {
                    $total_order_price+=$attr_val_obj->printAttributeValuePrice($item->id);
                } elseif ($attr_val_obj->isPercent()) {
                    $total_order_price+=$attr_val_obj->printAttributeValuePrice($item->id) * $item->quantity;
                }
            }
            unset($attribute_values); // delete previuos attrs
        }
        $tax_value = $tax * $total_order_price / 100 ;
        $cart_grand_total = $total_order_price + $tax_value ;
        $cart_grand_total = number_format((float)$cart_grand_total, 2, '.', '');
        return $cart_grand_total;
    }


    public function maxAppliedPoints(){
        $points = $this->profile->points;
        // can apply max 1000 point in each order
        // points applied as hundreds only
        /*
        $points = $points >= 1000 ? 1000 : $points ;
        if($points < 1000)
            $points = floor($points / 100) * 100;
        */

        $one_percent_discount = Setting::where('key','one_percent_discount_by_points')->first()->value;
        $frac = floor($points / $one_percent_discount) ;
        $max = 1000 - (1000 % $one_percent_discount) ;
        $points = $points >= $max ? $max : $points ;
        if($points < $max)
            $points = $frac * $one_percent_discount;
        return $points;
    }
}
