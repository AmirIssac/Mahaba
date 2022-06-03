<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'value',
        'active',
        'expired_at'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
