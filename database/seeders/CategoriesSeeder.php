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
            'name_en' => 'Whole Live',
            'name_ar' => 'كامل حي',
        ]);

        Category::create([
            'name_en' => 'cuts and seasonings',
            'name_ar' => 'مقطعات وتبلات',
        ]);
    }
}
