<?php

namespace Database\Seeders;

use App\Models\RejectReason;
use Illuminate\Database\Seeder;

class RejectReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reasons_array = array();
        $reason_array = array('name_en'=>'Address not valid','name_ar'=>'العنوان غير صالح');
        $reasons_array[] = $reason_array;
        $reason_array = array('name_en'=>'Bad smell','name_ar'=>'رائحة سيئة');
        $reasons_array[] = $reason_array;
        $reason_array = array('name_en'=>'Insufficient cooling degree','name_ar'=>'درجة التبريد غير كافية');
        $reasons_array[] = $reason_array;
        $reason_array = array('name_en'=>'Delivery delay','name_ar'=>'تأخر في عملية العتوصيل');
        $reasons_array[] = $reason_array;
        foreach($reasons_array as $reason){
            RejectReason::create([
                'name_en' => $reason['name_en'],
                'name_ar' => $reason['name_ar'],
            ]);
        }
    }
}
