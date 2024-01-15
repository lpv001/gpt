<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('x1')->nullable();
            $table->string('x2')->nullable();
            $table->string('n1')->nullable();
            $table->string('n2')->nullable();
            $table->string('n3')->nullable();
            $table->string('x3')->nullable();
            $table->string('n4')->nullable();
            $table->string('n5')->nullable();
            $table->string('n6')->nullable();
            $table->string('x4')->nullable();
            $table->string('file_prefix')->nullable();
            $table->string('data_files')->nullable();
            $table->string('gen_progress')->nullable();
            $table->integer('ndiff')->default('0');
            $table->integer('cdiff')->default('0');
            $table->tinyInteger('head_id');
            $table->string('head');
            $table->longText('format_data');
            $table->boolean('is_ready', 1)->default('0');
            $table->boolean('is_used', 1)->default('0');
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
        Schema::dropIfExists('codes');
    }
}
