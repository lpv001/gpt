<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProductPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_prices', function (Blueprint $table) {
            //
            $table->integer('qty_per_unit')->after('unit_id');
            $table->decimal('distributor')->after('sale_price');
            $table->decimal('wholesaler')->after('distributor');
            $table->decimal('retailer')->after('wholesaler');
            $table->decimal('buyer')->after('retailer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_prices', function (Blueprint $table) {
            //
        });
    }
}
