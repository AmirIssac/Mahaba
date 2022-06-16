<?php

namespace Database\Seeders;

use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

class AttributeValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {              // (attribute_id , value , price)
        $attribute_values = array(array(1,'عضم',5),array(1,'عصب',6),
                        array(2,'وسط',40),array(2,'كبير',60),
                        array(3,'تبلة غربية',5),array(3,'تبلة شرقية',5),array(3,'تبلة خاصة',5));
        foreach($attribute_values as $value){
            AttributeValue::create([
                'attribute_id' => $value[0],
                'value' => $value[1],
                'price' => $value[2],
            ]);
        }
    }
}
