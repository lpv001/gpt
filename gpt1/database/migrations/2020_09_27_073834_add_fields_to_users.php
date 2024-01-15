<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('sex')->nullable()->after('full_name');
            $table->date('dob')->nullable()->default(null)->after('sex');
            $table->string('profile_image')->nullable()->after('dob');
            $table->integer('city_province_id')->after('password');
            $table->integer('district_id')->after('city_province_id');
            $table->datetime('last_login')->nullable()->default(null)->after('district_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
