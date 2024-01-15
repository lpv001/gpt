<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Promotions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('shop_id')->unsigned();
            $table->String('code');
            $table->integer('promotion_type_id')->unsigned();
            $table->decimal('value');
            $table->integer('qty');
            $table->integer('balance');
            $table->date('start_date')->nullable()->format('d.m.Y');
            $table->date('end_date')->nullable()->format('d.m.Y');
            $table->string('image', 255)->nullable();
            $table->boolean('is_active')->default('1');
            $table->boolean('flag')->default('1');
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
        Schema::dropIfExists('promotions');
    }
}
