<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Store::create([
            'name_en' => 'Al Mahaba',
            'name_ar' => 'المحبة',
            'address' => 'دبي / القصيص ',
            'contact_phone' => '+971000000001',
            'address_latitude' => 25.276982 ,
            'address_longitude' => 55.372429 ,
        ]);

    }
}
