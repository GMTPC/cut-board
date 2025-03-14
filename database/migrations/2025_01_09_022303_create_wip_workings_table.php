<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWipWorkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('wip_working', function (Blueprint $table) {
        $table->bigInteger('ww_id')->unsigned()->primary(); // กำหนดเป็น PRIMARY KEY แต่ไม่เป็น IDENTITY
        $table->string('ww_group')->nullable();
        $table->string('ww_line');
        $table->integer('ww_wwt_index')->nullable();
        $table->string('ww_division')->nullable();
        $table->string('ww_status')->nullable();
        $table->text('ww_remark')->nullable();
        $table->dateTime('ww_start_date')->nullable();
        $table->dateTime('ww_lot_date')->nullable();
        $table->dateTime('ww_end_date')->nullable();
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
        Schema::dropIfExists('wip_workings');
    }
}
