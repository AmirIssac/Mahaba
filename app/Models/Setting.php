<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key','value',
    ];

    public static function isAcceptOrders(){
        $accept_orders = Setting::where('key','accept_orders')->first();
        if($accept_orders->value == 1)
            return true;
        else
            return false;
    }
}
