<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->dateTime('date_order_placed')->format('d.m.Y');
            $table->dateTime('date_order_paid')->nullable()->format('d.m.Y');
            $table->integer('order_status_id')->unsigned();
            $table->integer('delivery_option_id')->unsigned();
            $table->string('address_full_name')->nullable();
            $table->string('address_email')->nullable();
            $table->string('address_phone')->nullable();
            $table->string('address_street_address')->nullable();
            $table->integer('address_city_province_id')->unsigned()->nullable();
            $table->integer('address_district_id')->unsigned()->nullable();
            $table->string('phone_pickup')->nullable();
            $table->text('note')->nullable();
            $table->date('preferred_delivery_pickup_date')->nullable()->format('d.m.Y');
            $table->string('preferred_delivery_pickup_time')->nullable()->format('d.m.Y');
            $table->integer('payment_method_id')->unsigned();
            $table->dateTime('delivery_pickup_date')->nullable();
            $table->decimal('pickup_lat',10,6)->nullable();
            $table->decimal('pickup_lon',10,6)->nullable();
            $table->decimal('total', 10,2)->nullable();
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
