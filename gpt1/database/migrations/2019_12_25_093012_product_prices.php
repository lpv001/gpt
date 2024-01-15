<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductPrices extends Migration
{
    /**
     * Run the migrations.
     * @type_id: can be categorized by either shop or membership.
     * @return void
     */
    public function up()
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->integer('unit_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->decimal('unit_price');
            $table->decimal('sale_price');
            $table->boolean('is_active')->default('1');
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
        Schema::dropIfExists('product_prices');
    }
}
