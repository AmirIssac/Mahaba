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
            'name_en' => 'Al Jubail market',
            'name_ar' => 'سوق الجبيل',
            'address' => 'الشارقة / سوق الجبيل',
            'contact_phone' => '+971000000000',
            'address_latitude' => 25.35263 ,
            'address_longitude' => 55.38012 ,
        ]);

        Store::create([
            'name_en' => 'Al Mahaba',
            'name_ar' => 'المحبة',
            'address' => 'دبي / القصيص ',
            'contact_phone' => '+971000000001',
            'address_latitude' => 25.276982 ,
            'address_longitude' => 55.372429 ,
        ]);

        Store::create([
            'name_en' => 'Al Ain',
            'name_ar' => 'فرع العين',
            'address' => 'العين',
            'contact_phone' => '+971000000002',
            'address_latitude' => 24.207500 ,
            'address_longitude' => 55.744720 ,
        ]);
    }
}
