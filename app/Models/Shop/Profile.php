<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'address_address',
        'address_street',
        'address_building_apartment',
        'address_latitude',
        'address_longitude',
        'points',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
