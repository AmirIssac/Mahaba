<?php

namespace App\Models;

use App\Models\Shop\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestReference extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'reference',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
