<?php

namespace Database\Seeders;

use App\Models\Shop\Cart;
use App\Models\Shop\Favorite;
use App\Models\Shop\Profile;
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
            'name' => 'moaz',
            'email' => 'moaz@dabbagh.com',
            'password' => Hash::make('12345'),
        ]);
        Profile::create([
            'user_id' => $employee->id,
            'first_name' => 'Moaz',
            'last_name' => 'Aldaien',
            'phone' => '+971000000002',
            'address_address' => null,
            'address_latitude' => null,
            'address_longitude' => null,
        ]);
        Cart::create([
            'user_id' => $employee->id,
        ]);
        $employee->assignRole('employee');
        $employee->stores()->attach(1);

        $employee = User::create([
            'name' => 'rami',
            'email' => 'rami@dabbagh.com',
            'password' => Hash::make('12345'),
        ]);
        Profile::create([
            'user_id' => $employee->id,
            'first_name' => 'Rami',
            'last_name' => 'Ahmad',
            'phone' => '+971000000003',
            'address_address' => null,
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
        $employee->stores()->attach(2);
    }
}
