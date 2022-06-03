<?php

namespace Database\Seeders;

use App\Models\Shop\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name_en' => 'Whole Lamb',
            'name_ar' => 'خاروف كامل',
        ]);

        Category::create([
            'name_en' => 'Whole Lamb Cuts',
            'name_ar' => 'مقطعات الخاروف',
        ]);
    }
}
