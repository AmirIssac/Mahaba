<?php

namespace App\Models;

use App\Models\Shop\CartItem;
use App\Models\Shop\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = ['attribute_id' , 'value' , 'value_en' , 'value_type' , 'price'];


    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    public function cartItems()
    {
        return $this->belongsToMany(CartItem::class);
    }

    public function isValue(){
        if($this->value_type == 'value')
            return true;
        else
            return false;
    }


    public function isPercent(){
        if($this->value_type == 'percent')
            return true;
        else
            return false;
    }

    public function calcPercentValue($product_id){
        $product_price = Product::find($product_id)->price;
        return $this->price * $product_price / 100 ;
    }

    public function printAttributeValuePrice($product_id){
        if($this->isValue()){ // no need to use product_id
            return $this->price;
        }
        elseif($this->isPercent()){
            return $this->calcPercentValue($product_id);
        }
    }

}
