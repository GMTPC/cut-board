<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWipHoldingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wip_holding', function (Blueprint $table) {
            $table->bigIncrements('wh_id'); // Primary Key
            $table->bigInteger('wh_working_id')->unsigned()->nullable(); // Foreign Key
            $table->integer('wh_index')->default(0);
            $table->string('wh_barcode')->nullable();
            $table->string('wh_lot')->nullable();
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wip_holdings');
    }
}
