<?php

namespace App\Models;

use App\Models\Shop\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'address',
        'contact_phone',
        'address_latitude',
        'address_longitude',
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'store_user');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


}
