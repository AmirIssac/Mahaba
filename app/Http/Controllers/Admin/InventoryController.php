<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Shop\Category;
use App\Models\Shop\Discount;
use App\Models\Shop\Product;
use App\Models\Shop\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    //

    public function index(){
        $products = Product::orderBy('updated_at','DESC')->simplePaginate(10);
        $categories = Category::all();
        $discounts = Discount::all();
        $attributes = Attribute::with('attributeValues')->get();
        $attribute_values = AttributeValue::get();
        return view('Admin.Inventory.index',['products'=>$products,'categories'=>$categories,'discounts'=>$discounts,
                                             'attributes'=>$attributes,'attribute_values'=>$attribute_values]);
    }

    public function storeProduct(Request $request){
        /*
        $imagePath = $request->file('primary_image')->storeAs(
            'Products', $request->code
        );
        */
        $imagePath = $request->file('primary_image')->store('Products/'.$request->code, 'public');
        if($request->availability == 'yes')
            $availability = true;
        else
            $availability = false;
        $product = Product::create([
            'category_id' => $request->category_id,
            'code' => $request->code,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'description' => $request->description,
            'price' => $request->price,
            'unit' => $request->unit,
            'min_weight' => $request->min_weight,
            'increase_by' => $request->increase_by,
            'availability' => $availability,
            'image' => $imagePath,
        ]);
        // add additional images
        for($i=1;$i<5;$i++){
            if($request->file('image'.$i)){
                /*$image = $request->file('image1')->storeAs(
                    'Products', $request->code.'add'.$i
                );*/
                $image = $request->file('image'.$i)->store('Products/'.$request->code.'add'.$i, 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $image
                ]);
                }
        }
        // add options for this product (pivot table)
        /*
        $product->options()->attach($request->options);
        */

        // add additional attributes
        $attribute_values = $request->attribute_values;
        $product->attributeValues()->attach($attribute_values);
        return back();
    }

    public function editProductForm($id){
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $discounts = Discount::all();
        $attributes = Attribute::with('attributeValues')->get();
        $attribute_values = AttributeValue::get();
        return view('Admin.Inventory.edit_product_form',['product'=>$product,'categories'=>$categories,
                                                        'discounts'=>$discounts,'attributes'=>$attributes,'attribute_values'=>$attribute_values]);
    }

    public function updateProduct(Request $request , $id){
        $product = Product::findOrFail($id);
        if($request->file('primary_image')){
            // delete old image
            $img = str_replace('/storage', '', $product->image);
            Storage::delete('/public' . $img);
            $imagePath = $request->file('primary_image')->store('Products/'.$request->code, 'public');
        }
        else{   // no update on image
            $imagePath = $product->image;
        }
        if($request->availability == 'yes')
            $availability = true;
        else
            $availability = false;
        $product->update([
            'category_id' => $request->category_id,
            'code' => $request->code,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'description' => $request->description,
            'price' => $request->price,
            'unit' => $request->unit,
            'min_weight' => $request->min_weight,
            'increase_by' => $request->increase_by,
            'availability' => $availability,
            'image' => $imagePath,
        ]);
        // update additional images
        for($i=1;$i<5;$i++){
            if($request->file('image'.$i)){
                // delete old image and record
                if($product->productImages()->count() >= $i){   // exist so we delete
                $im = str_replace('/storage', '', $product->productImages[$i-1]->image);
                Storage::delete('/public' . $im);
                $product->productImages[$i-1]->delete();
                }
                $im = $request->file('image'.$i)->store('Products/'.$request->code.'add'.$i, 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $im,
                ]);
            }
        }
        return back();
    }

    public function storeNewDiscount(Request $request){
        $active = true;
        if(now() > $request->expired_at)
            $active = false;
        $discount = Discount::create([
            'type' => $request->discount_type,
            'value' => $request->discount_value,
            'active' => $active,
            'expired_at' => $request->expired_at,
        ]);

        foreach($request->apply_discount_on_products as $product_id){
                $product = Product::find($product_id);
                $product->update([
                    'discount_id' => $discount->id,
                ]);
        }

        return back();

    }

    public function editDiscountForm($id){
        $discount = Discount::find($id);
        $products = Product::all();
        return view('Admin.Inventory.edit_discount_form',['discount'=>$discount,'products' => $products]);
    }

    public function updateDiscount(Request $request,$id){
        $discount = Discount::find($id);
        $type = $request->discount_type;
        $value = $request->discount_value;
        $expired_at = $request->expired_at;
        $status = false;
        // check status by new expired input
        if($expired_at > now())
            $status = true;
        $discount->update([
            'type' => $type ,
            'value' => $value ,
            'expired_at' => $expired_at ,
            'active' => $status ,
        ]);
        // update products
        $old_products_discounted = Product::whereHas('discount',function($q) use ($discount){
          $q->where('id',$discount->id);
        })->get();
        $new_products_selected = $request->apply_discount_on_products;
        foreach($old_products_discounted as $old_product){
            if(in_array($old_product->id ,  $new_products_selected))
                continue;
            else
                Product::find($old_product->id)->update([
                    'discount_id' => null ,
                ]);
        }
        foreach($new_products_selected as $new_product){
            $product = Product::find($new_product);
            $product->update([
                'discount_id' => $discount->id,
            ]);
        }
        return back();
    }

    public function storeAttribute(Request $request){
        Attribute::create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
        ]);
        return back();
    }

    public function storeAttributeValue(Request $request){
        AttributeValue::create([
            'attribute_id' => $request->attribute,
            'value' => $request->name_ar,
            'value_en' => $request->name_en,
            'price' => $request->price,
            'value_type' => $request->type,
        ]);

        return back();
    }
}
