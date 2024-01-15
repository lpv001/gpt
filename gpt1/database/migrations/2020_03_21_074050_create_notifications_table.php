<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     * @action_id : tracking notification ID, if order notification action_id is order_id
     * @type : 1=NOTI_ORDER, 2=NOTI_SHOP, 3=NOTI_USER
     * @params : mics parameters
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0)->unsigned();
            $table->integer('action_id')->default(0)->unsigned();
            $table->boolean('type', 1)->default(0);
            $table->string('title');
            $table->text('body')->nullable();
            $table->boolean('is_read', 1)->default(0);
            $table->boolean('status', 1)->default(0);
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
        Schema::dropIfExists('notifications');
    }
}
