<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item_variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_item_id');
            $table->integer('product_id');
            $table->integer('variant_id');
            $table->integer('option_value_id');
            $table->double('variant_price');
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
        Schema::dropIfExists('order_item_variants');
    }
}
