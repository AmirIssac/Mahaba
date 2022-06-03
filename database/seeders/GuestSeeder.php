<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $guest = User::create([
            'name' => 'guest',
            'email' => 'guest@dabbaghfood.com',
            'password' => Hash::make('dabbagh12345'),
        ]);
        $guest->assignRole('guest');
    }
}
