<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWipbarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wipbarcodes', function (Blueprint $table) {
            $table->id('wip_id');
            $table->string('wip_barcode', 50);
            $table->integer('wip_amount');
            $table->unsignedBigInteger('wip_working_id');
            $table->unsignedBigInteger('wip_empgroup_id');
            $table->string('wip_sku_name', 255)->nullable();
            $table->integer('wip_index')->nullable();
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
        Schema::dropIfExists('wipbarcodes');
    }
}
