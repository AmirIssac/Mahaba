<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToAttributeValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attribute_values', function (Blueprint $table) {
            $table->string('value_en')->after('value')->default(null)->nullable();
            $table->enum('value_type',['value','percent'])->after('value_en')->default('value');  // ناخذ سعر الاضافة كقيمة اذا كانت اضافة اما ناخذها كنسبة من سعر المنتج الاساسي اذا كانت حجم على سبيل المثال
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attribute_values', function (Blueprint $table) {
            //
        });
    }
}
