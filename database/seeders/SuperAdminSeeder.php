<?php

namespace Database\Seeders;

use App\Models\Shop\Cart;
use App\Models\Shop\Favorite;
use App\Models\Shop\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Role::create(['name' => 'super_admin','name' => 'admin' , 'name' => 'employee' ,'name' => 'customer']);
        $superAdmin = User::create([
            'name' => 'Dabbagh',
            'email' => 'dabbagh@gmail.com',
            'password' => Hash::make('12345'),
        ]);
        Profile::create([
            'user_id' => $superAdmin->id,
            'first_name' => 'Dabbagh',
            'last_name' => 'Dabbagh',
            'phone' => '00971000000000',
            'address_address' => null,
            'address_street' => null,
            'address_building_apartment' => null,
            'address_latitude' => null,
            'address_longitude' => null,
        ]);
        Cart::create([
            'user_id' => $superAdmin->id,
        ]);
        Favorite::create([
            'user_id' => $superAdmin->id,
        ]);
        $superAdmin->assignRole('super_admin');
    }
}
