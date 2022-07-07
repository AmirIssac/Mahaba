<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'order_id',
        'price',
        'discount',
        'quantity',
        'item_attributes',
    ];

    // create accessor for item_attributes column
    public function getItemAttributesAttribute($value)
    {
        return unserialize($value);
    }

    public function printOrderItemAttributes(){
        $attributes = $this->item_attributes;
        $attributes_print = array();
        foreach($attributes as $attr){
            if($attr['value_type'] == 'value')
                $final_price = $attr['price'];
            elseif($attr['value_type'] == 'percent')
                $final_price = ( $attr['price'] * $this->price / 100 ) * $this->quantity;

            $attributes_print[] = array('id' => $attr['id'] , 'attribute_id' => $attr['attribute_id'],
            'value' => $attr['value'] , 'value_en' => $attr['value_en'] ,
            'final_price' => $final_price);
        }
        return $attributes_print;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
