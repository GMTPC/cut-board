<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWipZiptapeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('wip_ziptape', function (Blueprint $table) {
            $table->id('wz_id');
            $table->integer('wz_line');
            $table->foreignId('wz_worktime_id')->nullable()->constrained('work_times');
            $table->integer('wz_amount');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('wip_ziptape');
    }
};

