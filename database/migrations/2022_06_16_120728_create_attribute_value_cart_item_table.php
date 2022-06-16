<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeValueCartItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_value_cart_item', function (Blueprint $table) {  // pivot table
            $table->id();
            $table->unsignedBigInteger('attribute_value_id')->unsigned();
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values');//onDelete('set null');
            $table->unsignedBigInteger('cart_item_id')->unsigned();
            $table->foreign('cart_item_id')->references('id')->on('cart_items');//onDelete('set null');
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
        Schema::dropIfExists('attribute_value_cart_item');
    }
}
