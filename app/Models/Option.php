<?php

namespace App\Models;

use App\Models\Shop\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['name_en' , 'name_ar' , 'price'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
