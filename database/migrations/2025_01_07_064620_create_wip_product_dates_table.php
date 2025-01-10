<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWipProductDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wip_product_dates', function (Blueprint $table) {
            $table->id('wp_id');
            $table->unsignedBigInteger('wp_working_id');
            $table->unsignedBigInteger('wp_wip_id');
            $table->integer('wp_empdate_index_id')->nullable();
            $table->dateTime('wp_date_product');
            $table->unsignedBigInteger('wp_empgroup_id');
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
        Schema::dropIfExists('wip_product_dates');
    }
}
