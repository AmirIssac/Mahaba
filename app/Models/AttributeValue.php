<?php

namespace App\Models;

use App\Models\Shop\CartItem;
use App\Models\Shop\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = ['attribute_id' , 'value' , 'price'];

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
}
