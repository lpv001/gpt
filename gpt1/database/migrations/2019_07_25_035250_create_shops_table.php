<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->text('about')->nullable();
                $table->string('logo_image')->nullable();
                $table->string('cover_image')->nullable();
                $table->string('phone');
                $table->integer('country_id')->unsigned();
                $table->integer('city_province_id')->unsigned();
                $table->integer('district_id')->unsigned();
                $table->text('address')->nullable();
                $table->decimal('lat',10,6)->nullable();
                $table->decimal('lng',10,6)->nullable();
                $table->integer('membership_level')->unsigned();
                $table->boolean('status', 0)->default('0');
                $table->boolean('is_active', 1)->default('1');
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
        Schema::dropIfExists('shops');
    }
}
