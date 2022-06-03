<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderRejectReasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_reject_reason', function (Blueprint $table) { // pivot table
            $table->id();
            $table->unsignedBigInteger('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');//onDelete('set null');
            $table->unsignedBigInteger('reject_reason_id')->unsigned();
            $table->foreign('reject_reason_id')->references('id')->on('reject_reasons');//onDelete('set null');
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
        Schema::dropIfExists('order_reject_reason');
    }
}
