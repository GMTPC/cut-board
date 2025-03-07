<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWipColordateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('wip_colordate', function (Blueprint $table) {
            $table->id('wcd_id');
            $table->string('wcd_color');
            $table->integer('wcd_month_no');
            $table->text('wcd_remark')->nullable();
            $table->date('wcd_date')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('wip_colordate');
    }
};
