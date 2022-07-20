<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\DiscountDetail;
use App\Models\GuestReference;
use App\Models\OrderSystem;
use App\Models\Setting;
use App\Models\Shop\CartItem;
use App\Models\Shop\Discount;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\PaymentDetail;
use App\Models\Shop\Product;
use App\Models\Shop\Profile;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class OrderController extends Controller
{
    public function checkout(){
        $user = User::findOrFail(Auth::user()->id);
        $profile = $user->profile;
        // validate points
            //$points_applied = $request->points_applied;
            //$user_max_points = $user->maxAppliedPoints();
            $one_percent_discount = Setting::where('key','one_percent_discount_by_points')->first()->value;
            //if($points_applied > $user_max_points || $points_applied % $one_percent_discount != 0)
                //return abort(403);
        // save points applied to session
        //Session::put('points_applied',$points_applied);
        $cart = $user->cart;
        $hours_remaining_to_deliver = $cart->calculateDeliverTime();
        //$cart_items = $cart->cartItems;
        $cart_items = CartItem::with('attributeValues')->where('cart_id',$cart->id)->get();
        $date = now()->toDateString();
        $phone = substr($profile->phone, 5);
        $phone = '0'.$phone;
        $total_order_price = 0 ;
        $tax_row = Setting::where('key','tax')->first();
        $tax = (float) $tax_row->value;
        $total_order_price = $cart->getTotal();
        $tax_value = $tax * $total_order_price / 100 ;
        $order_grand_total = $total_order_price + $tax_value ;
        $order_grand_total = number_format((float)$order_grand_total, 2, '.', '');
        return view('Customer.order.checkout',['cart'=>$cart , 'cart_items' => $cart_items,'date' => $date, 'total_order_price' => $total_order_price,
                                                'order_grand_total' => $order_grand_total,
                                               'profile'=>$profile,'tax'=>$tax,'hours_remaining_to_deliver' => $hours_remaining_to_deliver,
                                                'phone' => $phone]);
    }

    public function guestCheckout(){
        $guest = User::whereHas('roles', function($q){
            $q->where('name', 'guest');
        })->first();                       // guest user
        $cart = Session::get('cart');
        $cart_items = collect();
        $date = now()->toDateString();
        $total_order_price = 0 ;
        $tax_row = Setting::where('key','tax')->first();
        $tax = (float) $tax_row->value;
        $cart_total = 0 ;
        if($cart){
            $total_order_price =  $guest->getGuestTotalCart($cart , $cart_items);

            $tax_value = $tax * $total_order_price / 100 ;
            $order_grand_total = $total_order_price + $tax_value ;
            $order_grand_total = number_format((float)$order_grand_total, 2, '.', '');
            $hours_remaining_to_deliver = $guest->calculateGuestDeliverTime();
            return view('Guest.order.checkout',['cart'=>$cart , 'cart_items' => $cart_items,'date' => $date, 'total_order_price' => $total_order_price,'tax'=>$tax,'guest'=>$guest,
                                                'order_grand_total' => $order_grand_total,'hours_remaining_to_deliver' => $hours_remaining_to_deliver]);
        }
    }

    public function submitOrder(Request $request){
        $validatedData = $request->validate([
            'first_name' => ['required', 'max:200'],
            'last_name' => ['required','max:200'],
            'phone' => ['required','digits:10','regex:/(05)[0-9]{8}/'],
            'email' => ['required','email'],
            'address1' => ['required_without:address2'],
            'address2' => ['required_without:address1'],
            'address_street1' => ['required_without:address_street2'],
            'address_street2' => ['required_without:address_street1'],
            'address_building_apartment1' => ['required_without:address_building_apartment2'],
            'address_building_apartment2' => ['required_without:address_building_apartment1'],
        ]);

        $user = User::findOrFail(Auth::user()->id);
        $cart = $user->cart;
        $cart_items = $cart->cartItems;
        $total_order_price = 0 ;
        $tax_row = Setting::where('key','tax')->first();
        $tax = (float) $tax_row->value;
        /*
        $number_of_today_orders = Order::whereYear('created_at',now()->year)->whereMonth('created_at',now()->month)
                                    ->whereDay('created_at',now()->day)->count();
        $date = Carbon::now();
        $date = $date->format('ymd');    // third segment
        $number = $date.str_pad($number_of_today_orders + 1, 4, "0", STR_PAD_LEFT);
        */
        // $order_items_arr is array of arrays
        $order_items_arr = array();
        $total_order_price = 0 ;
        $tax_value = 0 ;
        $grand_order_total = $cart->getTotalSubmittingOrder($total_order_price , $order_items_arr , $tax_value );
        if($request->payment_method == 'cash')
            $order_status = 'pending';
        else
            $order_status = 'pending'; // we will keep it pending I think

        $address = $request->address1;   // default main city
        $address_street = $request->address_street1;   // default main street
        $address_building_apartment = $request->address_building_apartment1;   // default main building
        if($request->address2)
            $address = $request->address2;
        if($request->address_street2)
            $address_street = $request->address_street2;
        if($request->address_building_apartment2)
            $address_building_apartment = $request->address_building_apartment2;

        $estimated_time = $cart->calculateDeliverTime(true);

        DB::beginTransaction();
       // try{
                $store_id = Store::first()->id;  // mahaba
                $order_index = Order::whereYear('created_at',now()->year)->count();
                $number = str_pad($order_index + 1, 6, "0", STR_PAD_LEFT);
                $order = Order::create([
                    'user_id' => $user->id ,
                    'store_id' => $store_id,
                    'number' =>  $number ,
                    'status' => $order_status ,
                    'sub_total' => $total_order_price ,
                    'tax_ratio' => $tax ,
                    'tax_value' => $tax_value ,
                    'shipping' => 0 ,
                    'total' => $grand_order_total ,
                    'first_name' => $request->first_name ,
                    'last_name' => $request->last_name ,
                    'phone' => $request->phone ,
                    'email' => $request->email ,
                    'address' => $address ,
                    'address_street' => $address_street ,
                    'address_building_apartment' => $address_building_apartment ,
                    'customer_note' => $request->customer_note ,
                    'estimated_time' => $estimated_time ,
                ]);
                // super admin
                $super_admin = User::role('super_admin')->first();
                $order_system = OrderSystem::create([
                    'order_id' => $order->id,
                    'user_id' =>  $super_admin->id,
                    'status' => $order->status,
                ]);
                // inserting order_items
                foreach($order_items_arr as $order_item){
                    OrderItem::create([
                        'order_id' => $order->id ,
                        'product_id' => $order_item['product_id'] ,
                        'price' => $order_item['price'] ,
                        'discount' => $order_item['discount'] ,
                        'quantity' => $order_item['quantity'] ,
                        'item_attributes' => $order_item['attribute_values'],
                    ]);
                }
                // create discount
                /*
                if(Session::get('points_applied')){
                    DiscountDetail::create([
                        'user_id' => $user->id ,
                        'order_id'=> $order->id ,
                        'type' => 'points',
                        'percent' => Session::get('discount_percent'),
                        'value' => Session::get('discount'),
                        'code' => Session::get('points_applied'),
                    ]);
                }
                */
                // create payment_details
                if($request->payment_method == 'cash'){
                    PaymentDetail::create([
                        'user_id' => $user->id ,
                        'order_id' => $order->id ,
                        'amount' => $order->total ,
                        'provider' => 'cash' ,
                        'status' => 'pending' ,
                    ]);
                }
                // delete items from cart
                foreach($cart_items as $cart_item){
                        foreach($cart_item->attributeValues as $attr_val)
                            $cart_item->attributeValues()->detach($attr_val->id); // remove all attr for the cart_item

                        $cart_item->delete();
                }
                DB::commit();
        //}
        //catch(\Exception $e) {
        //    DB::rollback();
       //     return 'opss something gone wrong !';
      //  }

        return redirect(route('order.details',Crypt::encrypt($order->id)));

    }

    public function submitOrderAsGuest(Request $request){
        $validatedData = $request->validate([
            'first_name' => ['required', 'max:200'],
            'last_name' => ['required','max:200'],
            'phone' => ['required','digits:10','regex:/(05)[0-9]{8}/'],
            'email' => ['required','email'],
            'address2' => ['required'],
            'address_street2' => ['required'],
            'address_building_apartment2' => ['required'],
        ]);

        $guest = User::whereHas('roles', function($q){
            $q->where('name', 'guest');
        })->first();                       // guest user
        $cart = Session::get('cart');
        $cart_items = collect();
        $total_order_price = 0 ;
        $tax_row = Setting::where('key','tax')->first();
        $tax = (float) $tax_row->value;

        if($cart){
            foreach($cart as $c_item){
                $item = Product::find($c_item['product_id']);  // but we have to take quantity too (its not stored in product object its stored in cart_item table and we dont have cart_item in session process)
                $item->quantity = $c_item['quantity'];
                $item->attributes = $c_item['attributes'];
                $cart_items->add($item);
            }
            //$order_items_arr = array(array());
            // $order_items_arr is array of arrays
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
                        if(count($item->attributes)>0)
                            foreach($item->attributes as $attr_val){
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
                        if(count($item->attributes)>0)
                            foreach($item->attributes as $attr_val){
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
                        if(count($item->attributes)>0)
                            foreach($item->attributes as $attr_val){
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
                foreach ($item->attributes as $attr_val) {
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
            $grand_order_total = $total_order_price + $tax_value ;
            if($request->payment_method == 'cash')
                $order_status = 'pending';
            else
                $order_status = 'preparing';
            /*
            $address = $request->address1;   // default main address
            if($request->address2)
                $address = $request->address2;
            */
            $address = $request->address2;
            $address_street = $request->address_street2;
            $address_building_apartment = $request->address_building_apartment2;

            $estimated_time = $guest->calculateGuestDeliverTime(true);
            // create guest reference code
            do{
                $reference = Str::random(6);
                $check_reference = GuestReference::where('reference',$reference)->first();
            }while($check_reference);
            DB::beginTransaction();
            $store_id = Store::first()->id;  // mahaba
            $order_index = Order::whereYear('created_at',now()->year)->count();
            $number = str_pad($order_index + 1, 6, "0", STR_PAD_LEFT);
            $order = Order::create([
                'user_id' => $guest->id ,
                'store_id' => $store_id ,
                'number' =>  $number ,
                'status' => $order_status ,
                'sub_total' => $total_order_price ,
                'tax_ratio' => $tax ,
                'tax_value' => $tax_value ,
                'shipping' => 0 ,
                'total' => $grand_order_total ,
                'first_name' => $request->first_name ,
                'last_name' => $request->last_name ,
                'phone' => $request->phone ,
                'email' => $request->email ,
                'address' => $address ,
                'address_street' => $address_street ,
                'address_building_apartment' => $address_building_apartment ,
                'customer_note' => $request->customer_note ,
                'estimated_time' => $estimated_time ,
            ]);
            $order_reference = GuestReference::create([
                'order_id' => $order->id,
                'reference' => $reference,
            ]);
            // super admin
            $super_admin = User::role('super_admin')->first();
            $order_system = OrderSystem::create([
                'order_id' => $order->id,
                'user_id' =>  $super_admin->id,
                'status' => $order->status,
            ]);
            // inserting order_items
            foreach($order_items_arr as $order_item){
                OrderItem::create([
                    'order_id' => $order->id ,
                    'product_id' => $order_item['product_id'] ,
                    'price' => $order_item['price'] ,
                    'discount' => $order_item['discount'] ,
                    'quantity' => $order_item['quantity'] ,
                    'item_attributes' => $order_item['attr_vals'],
                ]);
            }
            // create payment_details
            if($request->payment_method == 'cash'){
                PaymentDetail::create([
                    'user_id' => $guest->id ,
                    'order_id' => $order->id ,
                    'amount' => $order->total ,
                    'provider' => 'cash' ,
                    'status' => 'pending' ,
                ]);
            }
            Db::commit();
            // delete items from session cart
            Session::forget('cart');
            return redirect(route('order.details.reference',$reference));
            //return redirect(route('order.details',$order->id));
            //return redirect('/')->with('success','your order created successfully');
        }  // end if ($cart)
    }

    public function details($id){
        $order = Order::findOrFail(Crypt::decrypt($id));
        $order_items = $order->orderItems;
        return view('Customer.order.order_details',['order' => $order , 'order_items' => $order_items]);
    }

    public function detailsByReference($reference){
        $guest = true;
        $guestReference = GuestReference::where('reference',$reference)->first();
        if(!$guestReference)
            abort(404);
        $order = $guestReference->order;
        $order_items = $order->orderItems;
        return view('Customer.order.order_details',['order' => $order , 'order_items' => $order_items ,
        'reference' => $guestReference->reference , 'guest' => $guest]);
    }

    public function showMyOrders(){
        if (Auth::user()) {
            $user = User::findOrFail(Auth::user()->id);
            $orders = $user->orders()->orderBy('updated_at', 'DESC')->simplePaginate(10);
            return view('Customer.order.my_orders', ['orders'=>$orders]);
        }
        else{
            return view('Guest.order.my_orders');
        }
    }

    public function viewOrder($id){
        $order = Order::findOrFail(Crypt::decrypt($id));
        $order_items = $order->orderItems;
        /*
        $order_items = $order_items->map(function($order_item, $key) {  // map for get unserialize
            return [
                    'id' => $order_item->id,
                    'product_id' => $order_item->product_id,
                    'order_id' => $order_item->order_id,
                    'price' => $order_item->price,
                    'discount' => $order_item->discount,
                    'quantity' => $order_item->quantity,
                    'item_attributes' => unserialize($order_item->item_attributes),
                ];
            });  // end mapping
            //return $order_items;
            */
        if($order->orderSystems()->count() > 0){
            $order_process = $order->orderSystems->last();
            $last_update_status = $order_process->updated_at->diffForHumans();
        }
        else{
            $last_update_status = $order->updated_at->diffForHumans();
        }
        return view('Customer.order.view_order',['order' => $order , 'order_items' => $order_items,'last_update_status'=>$last_update_status]);
    }

    public function trackByReference(Request $request){
        $reference = $request->reference;
        $guest_ref = GuestReference::where('reference',$reference)->first();
        if(!$guest_ref)
            abort(404);
        $order = $guest_ref->order;
        $order_items = $order->orderItems;
        if($order->orderSystems()->count() > 0){
            $order_process = $order->orderSystems->last();
            $last_update_status = $order_process->updated_at->diffForHumans();
        }
        else{
            $last_update_status = $order->updated_at->diffForHumans();
        }
        return view('Customer.order.view_order',['order' => $order , 'order_items' => $order_items,'last_update_status'=>$last_update_status]);
    }
}
