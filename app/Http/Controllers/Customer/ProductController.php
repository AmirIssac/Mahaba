<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductRate;
use App\Models\Shop\CartItem;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{

    public function categories(){
        $categories = Category::get();
        return view('Customer.product.categories',['categories'=>$categories]);
    }

    public function indexByCategory($id){
        $category = Category::findOrFail($id);
        $categories = Category::all();
        //$attributes_list = Attribute::get();
        $products = $category->products;
        if($products->count() == 1){ // enter the product directly
            // I want just attribute collections that contain at least one attribute value attached to this product
            $attributes_list = Attribute::has('attributeValues')->whereHas('attributeValues.products', function($q) use ($products){
                $q->where('product_id', $products->first()->id);
            })->get();
            $exist_rate = false ;
            $user_rate_value = 0 ;
            $rate = $products->first()->rating();
            $reviews = $products->first()->reviews();
            if(Auth::user()){
                $user_rate = ProductRate::where('product_id',$products->first()->id)->where('user_id',Auth::user()->id)->first();
                if($user_rate){
                    $exist_rate = true;
                    $user_rate_value = $user_rate->value;
                }
                return view ('Customer.product.view',['product'=>$products->first(),'rate'=>$rate,'exist_rate'=>$exist_rate,'user_rate_val' => $user_rate_value,
                                                      'reviews'=>$reviews,'attributes_list'=>$attributes_list]);
            }
            else{
                return view('Customer.product.view', ['product'=>$products->first(),'rate'=>$rate ,'exist_rate'=>$exist_rate,'user_rate_val' => $user_rate_value,
                                                      'reviews'=>$reviews,'attributes_list'=>$attributes_list]);
            }
        }
        else
            return view('Customer.index_by_category',['categories'=>$categories,'products'=>$products]);
    }

    public function filter(Request $request){
        $categories = Category::all();
        $min = $request->price_min;
        $max = $request->price_max;
        $products = Product::where(function($query) use ($request){
            $query->where('name_en','like','%'.$request->search.'%')
            ->orWhere('name_ar','like','%'.$request->search.'%');
            })
            ->whereIn('category_id', $request->categories)
            ->whereBetween('price',array($min,$max))
            ->get();
        // price filter on products
        foreach($products as $key => $value){
             //return $products[$key];
             //$products->forget($key);
             if($products[$key]->hasDiscount()){
                if($products[$key]->isPercentDiscount()){
                    $discount = $products[$key]->price * $products[$key]->discount->value / 100;
                    $new_price = $products[$key]->price - $discount;
                }
                else
                    $new_price = $products[$key]->price - $products[$key]->discount->value;
                if($new_price < $min || $new_price > $max)
                    $products->forget($key);  // delete the item from the collection
             } // end hasDiscount condition
             else{  // no Discount
                if($products[$key]->price < $min || $products[$key]->price > $max)
                    $products->forget($key);  // delete the item from the collection
             }
        }
        return view('Customer.index_by_category',['categories'=>$categories,'products'=>$products]);
    }

    public function viewProduct($id){
        $product = Product::with('attributeValues.attribute')->findOrFail(Crypt::decrypt($id));
        //$attributes_list = Attribute::get();
        // I want just attribute collections that contain at least one attribute value attached to this product
        $attributes_list = Attribute::has('attributeValues')->whereHas('attributeValues.products', function($q) use ($product){
            $q->where('product_id', $product->id);
        })->get();
        $exist_rate = false ;
        $user_rate_value = 0 ;
        $rate = $product->rating();
        $reviews = $product->reviews();
        if(Auth::user()){
            $user_rate = ProductRate::where('product_id',$product->id)->where('user_id',Auth::user()->id)->first();
            if($user_rate){
                $exist_rate = true;
                $user_rate_value = $user_rate->value;
            }
            return view ('Customer.product.view',['product'=>$product,'rate'=>$rate,'exist_rate'=>$exist_rate,'user_rate_val' => $user_rate_value,
                                                  'reviews'=>$reviews,'attributes_list'=>$attributes_list]);
        }
        else{
            return view ('Customer.product.view',['product'=>$product,'rate'=>$rate ,'exist_rate'=>$exist_rate,'user_rate_val' => $user_rate_value,
                                                  'reviews'=>$reviews,'attributes_list'=>$attributes_list]);
        }
    }

    /*
    public function addProductToCart(Request $request,$id){
        $product = Product::findOrFail($id);
        $user = User::findOrFail(Auth::user()->id);
        $cart = $user->cart;
        //$cart->products()->attach([$product->id]);
        // check if product repeated in cart so we increase quantity
        $check_product = CartItem::where('cart_id',$cart->id)->where('product_id',$product->id)->first();
        if($check_product){
            $check_product->update([
                'quantity' => $check_product->quantity + $request->quantity,   // quantity is the weight in gram
            ]);
        }
        else{
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }
        return response('success');
    }
    */

    /*
    public function addProductToCart(Request $request,$id){
        $product = Product::findOrFail($id);
        $attribute_values_jquery_serialized = $request->attribute_values;
        //$attribute_values = array();
        //parse_str($attribute_values_jquery_serialized, $attribute_values);
        //return $attribute_values_jquery_serialized[1]['value'];

        //foreach($attribute_values as $value)
            //return $value->value;
        //return parse_str($attribute_values_jquery_serialized, $attribute_values);
        //return unserialize($attribute_values_jquery_serialized);
        //$user = User::findOrFail(Auth::user()->id);
        if(Auth::user()){     // logged user
                /*
                $user = User::findOrFail(Auth::user()->id);
                $cart = $user->cart;
                //$cart->products()->attach([$product->id]);
                // check if product repeated in cart so we increase quantity
                $check_product = CartItem::where('cart_id',$cart->id)->where('product_id',$product->id)->first();
                if($check_product){
                    $check_product->update([
                        'quantity' => $check_product->quantity + $request->quantity,   // quantity is the weight in gram
                    ]);
                }
                else{
                    $cartItem = CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'quantity' => $request->quantity,
                    ]);
                }
                return response('success');
                /
                $user = User::findOrFail(Auth::user()->id);
                $cart = $user->cart;
                //$cart->products()->attach([$product->id]);
                    $cartItem = CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'quantity' => $request->quantity,
                    ]);
                    // add attributes
                    if(is_array($attribute_values_jquery_serialized))  // there is attributes to add
                        for ($i = 0 ; $i < sizeof($attribute_values_jquery_serialized) ; $i++) {
                            $attribute_value_id = $attribute_values_jquery_serialized[$i]['value'];  // attribute_value id
                            $cartItem->attributeValues()->attach($attribute_value_id);
                        }
                return response('success');
        }
        else{    // Guest
                if(!Session::get('cart')){    // the first time we add to cart so we initialize the cart array to store in Session
                //$cart = array(array());   // array contain the each cart_item as array
                $cart_item = array('product_id'=>$product->id,'quantity'=>$request->quantity);
                $cart[]=$cart_item;
                Session::put('cart',$cart);
                }
                else{    // no init needed
                    $cart = Session::get('cart');
                    $product_exist = false ;
                    $i = 0 ;
                    foreach($cart as $item){
                        if($item['product_id'] == $product->id){  // product exist in the cart before
                            $new_quantity = $request->quantity ;
                            $cart[$i]['quantity'] = $cart[$i]['quantity'] + $new_quantity ;
                            $product_exist = true ;
                            break;
                        }
                        $i++;
                    }
                    Session::put('cart',$cart);
                    if(!$product_exist){
                        $cart_item = array('product_id'=>$product->id,'quantity'=>$request->quantity);
                        $cart[]=$cart_item;
                        Session::put('cart',$cart);
                    }
                }
                return response('success');
        }
    }
    */

    public function addProductToCart(Request $request,$id){
        $product = Product::findOrFail($id);
        $attribute_values_jquery_serialized = $request->attribute_values;
        //$attribute_values = array();
        //parse_str($attribute_values_jquery_serialized, $attribute_values);
        //return $attribute_values_jquery_serialized[1]['value'];

        //foreach($attribute_values as $value)
            //return $value->value;
        //return parse_str($attribute_values_jquery_serialized, $attribute_values);
        //return unserialize($attribute_values_jquery_serialized);
        //$user = User::findOrFail(Auth::user()->id);
        if(Auth::user()){     // logged user
                /*
                $user = User::findOrFail(Auth::user()->id);
                $cart = $user->cart;
                //$cart->products()->attach([$product->id]);
                // check if product repeated in cart so we increase quantity
                $check_product = CartItem::where('cart_id',$cart->id)->where('product_id',$product->id)->first();
                if($check_product){
                    $check_product->update([
                        'quantity' => $check_product->quantity + $request->quantity,   // quantity is the weight in gram
                    ]);
                }
                else{
                    $cartItem = CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'quantity' => $request->quantity,
                    ]);
                }
                return response('success');
                */
                $user = User::findOrFail(Auth::user()->id);
                $cart = $user->cart;
                //$cart->products()->attach([$product->id]);
                    $cartItem = CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'quantity' => $request->quantity,
                    ]);
                    // add attributes
                    if(is_array($attribute_values_jquery_serialized))  // there is attributes to add
                        for ($i = 0 ; $i < sizeof($attribute_values_jquery_serialized) ; $i++) {
                            $attribute_value_id = $attribute_values_jquery_serialized[$i]['value'];  // attribute_value id
                            $cartItem->attributeValues()->attach($attribute_value_id);
                        }
                return response('success');
        }
        else{    // Guest
                if(!Session::get('cart')){    // the first time we add to cart so we initialize the cart array to store in Session
                //$cart = array(array());   // array contain the each cart_item as array
                // add attributes
                $attributes = array();
                if(is_array($attribute_values_jquery_serialized)){  // there is attributes to add
                    for ($i = 0 ; $i < sizeof($attribute_values_jquery_serialized) ; $i++) {
                        $attribute_value_id = $attribute_values_jquery_serialized[$i]['value'];  // attribute_value id
                        $attr = AttributeValue::find($attribute_value_id);
                        //$attributes[] = array('id' => $attr->id , 'price' => $attr->price , 'value_type' => $attr->value_type);
                        $attributes[] = array('id' => $attr->id ,'attribute_id' => $attr->attribute_id ,'value'=>$attr->value,
                                              'value_en'=>$attr->value_en,'value_type' => $attr->value_type, 'price' => $attr->price ,);
                    }
                }
                $cart_item = array('product_id'=>$product->id,'quantity'=>$request->quantity,'attributes'=>$attributes);
                $cart[]=$cart_item;
                Session::put('cart',$cart);
                }
                else{    // no init needed
                    $cart = Session::get('cart');
                    Session::put('cart',$cart);
                    // add attributes
                    $attributes = array();
                    if(is_array($attribute_values_jquery_serialized)){  // there is attributes to add
                        for ($i = 0 ; $i < sizeof($attribute_values_jquery_serialized) ; $i++) {
                            $attribute_value_id = $attribute_values_jquery_serialized[$i]['value'];  // attribute_value id
                            $attr = AttributeValue::find($attribute_value_id);
                            //$attributes[] = array('id' => $attr->id , 'price' => $attr->price , 'value_type' => $attr->value_type);
                            $attributes[] = array('id' => $attr->id ,'attribute_id' => $attr->attribute_id ,'value'=>$attr->value,
                                                  'value_en'=>$attr->value_en,'value_type' => $attr->value_type, 'price' => $attr->price ,);
                        }
                    }
                    $cart_item = array('product_id'=>$product->id,'quantity'=>$request->quantity,'attributes'=>$attributes);
                    $cart[]=$cart_item;
                    Session::put('cart',$cart);
                }
                return response('success');
        }
    }

    public function ProductToFavorite($id){
        $product = Product::findOrFail($id);
        if(Auth::user()){     // logged user
                $user = User::findOrFail(Auth::user()->id);
                $favorite = $user->favorite;
                // check if product repeated in favorite so we remove it
                $favorite_products = $favorite->products;
                $check_product = false;
                foreach($favorite_products as $favorite_product){
                    if($favorite_product->id == $product->id){
                        $check_product = true;
                        break;
                    }
                }
                if(!$check_product){
                    $favorite->products()->attach($product->id);
                    return response('added');
                }
                else{
                    $favorite->products()->detach($product->id);
                    return response('removed');
                }

        }
    }

    public function removeFromFavorite($id){
        $product = Product::findOrFail($id);
        if(Auth::user()){     // logged user
                $user = User::findOrFail(Auth::user()->id);
                $favorite = $user->favorite;
                $favorite->products()->detach($product->id);
                return back();
        }
    }

    public function updateProductCart(Request $request , $id){
            $product = Product::findOrFail($id);
            if(Auth::user()){    // Customer
                $user = User::findOrFail(Auth::user()->id);
                $cart = $user->cart;
                //$cart->products()->attach([$product->id]);
                // check if product repeated in cart so we increase quantity
                $check_product = CartItem::where('cart_id',$cart->id)->where('product_id',$product->id)->first();
                if($check_product){
                    $check_product->update([
                        'quantity' => (int) $request->quantity,
                    ]);
                }
                return response('success');
            }
            else{   // Guest
                $cart = Session::get('cart');
                $i = 0 ;
                foreach($cart as $item){
                    if($item['product_id'] == $product->id){  // product exist in the cart before
                        $new_quantity = (int) $request->quantity ;
                        $cart[$i]['quantity'] =  $new_quantity ;
                        break;
                    }
                    $i++;
                }
                Session::put('cart',$cart);
                return response('success');
            }
    }

    public function rateProduct(Request $request,$id){
        // check if not auth || rate this product before so we prevent the function of rating
        if(!Auth::user())
            return back();
        if($request->rate > 5 || $request->rate < 1)
            return back();
        $user = User::find(Auth::user()->id);
        $product = Product::findOrFail($id);
        $check = $user->productsRate()->where('product_id',$product->id)->first();
        if($check)  // prvent user from rating same product twice
            return back();
        switch($request->rate){
            case 1 : $value = 1 ;
                     break;
            case 2 : $value = 2 ;
                     break;
            case 3 : $value = 3 ;
                     break;
            case 4 : $value = 4 ;
                     break;
            case 5 : $value = 5 ;
                     break;
        }
        ProductRate::create([
            'product_id' => $product->id ,
            'user_id' => Auth::user()->id ,
            'value' => $value,
        ]);
        return back();
    }
}
