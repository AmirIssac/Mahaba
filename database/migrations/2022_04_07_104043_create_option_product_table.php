<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_product', function (Blueprint $table) {  // pivot table
            $table->id();
            $table->unsignedBigInteger('option_id')->unsigned();
            $table->foreign('option_id')->references('id')->on('options');//onDelete('set null');
            $table->unsignedBigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');//onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('option_product');
    }
}
