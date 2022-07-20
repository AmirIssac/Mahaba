<?php

namespace Database\Seeders;

use App\Models\Shop\Cart;
use App\Models\Shop\Favorite;
use App\Models\Shop\Profile;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employee = User::create([
            'name' => 'rami',
            'email' => 'rami@dabbagh.com',
            'password' => Hash::make('12345'),
        ]);
        Profile::create([
            'user_id' => $employee->id,
            'first_name' => 'Rami',
            'last_name' => 'Ahmad',
            'phone' => '00971000000003',
            'address_address' => null,
            'address_street' => null,
            'address_building_apartment' => null,
            'address_latitude' => null,
            'address_longitude' => null,
        ]);
        Cart::create([
            'user_id' => $employee->id,
        ]);
        Favorite::create([
            'user_id' => $employee->id,
        ]);
        $employee->assignRole('employee');
        $store_id = Store::first()->id;
        $employee->stores()->attach($store_id);
    }
}
