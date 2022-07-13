<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            //$table->tinyInteger('percent');
            //$table->decimal('value',10,2);
            $table->enum('type',['percent','value']);
            $table->decimal('value',10,2);
            $table->boolean('active');
            $table->timestamp('expired_at');  // must remove current timestamp default value and on update
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
        Schema::dropIfExists('discounts');
    }
}
