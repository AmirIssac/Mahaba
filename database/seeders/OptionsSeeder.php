<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Seeder;

class OptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Option::create([
            'name_en' => 'with bones',
            'name_ar' => 'مع العظم',
        ]);

        Option::create([
            'name_en' => 'little fat',
            'name_ar' => 'قليل الدهن',
        ]);

        Option::create([
            'name_en' => 'medium fat',
            'name_ar' => 'دهن متوسط',
        ]);

        Option::create([
            'name_en' => 'a lot of fat',
            'name_ar' => 'الكثير من الدهن',
        ]);

        Option::create([
            'name_en' => 'minced',
            'name_ar' => 'مفروم',
        ]);

        Option::create([
            'name_en' => 'big pieces',
            'name_ar' => 'قطع كبيرة',
        ]);

        Option::create([
            'name_en' => 'small pieces',
            'name_ar' => 'قطع صغيرة',
        ]);
    }
}
