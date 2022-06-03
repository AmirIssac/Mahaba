<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   // including transfering process from admin thats mean if order not have any order system process so the order created from customer and no body do anything to it yet
        Schema::create('order_systems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');//onDelete('set null');
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');//onDelete('set null');
            $table->enum('status',['pending','preparing','shipping','delivered','failed','cancelled','rejected']);
            $table->string('employee_note')->nullable();
            $table->dateTime('estimated_time')->nullable();  // determined from the dashboard after accepting the order
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
        Schema::dropIfExists('order_systems');
    }
}
