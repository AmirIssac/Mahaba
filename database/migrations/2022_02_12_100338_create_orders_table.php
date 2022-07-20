<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {    // Customer Order
            //Schema::dropIfExists('orders');
            //Schema::dropIfExists('order_items');
            $table->id();
            $table->unsignedBigInteger('user_id')->unsigned();   // customer
            $table->foreign('user_id')->references('id')->on('users');//onDelete('set null');
            $table->unsignedBigInteger('store_id')->unsigned()->nullable()->default(null);
            $table->foreign('store_id')->references('id')->on('stores');//onDelete('set null');
            $table->string('number');   // order number
            $table->enum('status',['pending','preparing','shipping','delivered','failed','cancelled','rejected']);
            $table->decimal('sub_total',10,2);   // the total price of the order items
            $table->decimal('tax_ratio',3,2);   // the tax ratio
            $table->decimal('tax_value',10,2);   // the total tax value of the order
            $table->decimal('shipping',10,2);   // the shipping charges of the order items
            $table->decimal('total',10,2);   // the total price of the order items including tax & shipping
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone',20);
            $table->string('email',75);
            $table->string('address');  // city , state
            $table->string('customer_note')->nullable();
            //$table->string('employee_note')->nullable();
            $table->timestamp('estimated_time')->nullable();  // determined from the dashboard after accepting the order
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
        Schema::dropIfExists('orders');
    }
}
