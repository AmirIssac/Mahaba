<?php

namespace App\Models;

use App\Models\Shop\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
    ];

    public function orders(){
        return $this->belongsToMany(Order::class);
    }
}
