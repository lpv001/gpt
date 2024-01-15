<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->unsigned();
            $table->integer('payment_method_id')->unsigned();
            $table->integer('payment_account_id')->unsigned();
            $table->decimal('amount',10,2);
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('code')->nullable();
            $table->string('screenshot')->nullable();
            $table->string('memo')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
