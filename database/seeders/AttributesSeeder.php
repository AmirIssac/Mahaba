<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attributes = array(array('extras','اضافات'),array('sizes','احجام'),array('seasonings','تبلات'));
        foreach($attributes as $attribute){
            Attribute::create([
                'name_en' => $attribute[0],
                'name_ar' => $attribute[1],
            ]);
        }
    }
}
