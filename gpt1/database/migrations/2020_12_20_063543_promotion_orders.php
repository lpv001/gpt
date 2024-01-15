<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PromotionOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('promotion_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->integer('qty');
            $table->decimal('total', 10,2)->nullable();
            $table->integer('delivery_id')->unsigned();
            $table->string('delivery_address')->nullable();
            $table->integer('delivery_city_id')->unsigned()->nullable();
            $table->integer('delivery_district_id')->unsigned()->nullable();
            $table->string('delivery_phone')->nullable();
            $table->string('delivery_email')->nullable();
            $table->dateTime('delivery_date')->format('d.m.Y');
            $table->dateTime('order_date')->format('d.m.Y');
            $table->dateTime('approval_date')->format('d.m.Y');
            $table->dateTime('date_order_paid')->nullable()->format('d.m.Y');
            $table->integer('status')->unsigned();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('promotion_orders');
    }
}
