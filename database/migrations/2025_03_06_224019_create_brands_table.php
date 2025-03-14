<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('brands', function (Blueprint $table) {
            $table->id('brd_id');

            // แก้ไข Foreign Key ให้ชี้ไปที่ ww_id ใน wip_workings
            $table->unsignedBigInteger('brd_working_id')->nullable();
            $table->foreign('brd_working_id')->references('ww_id')->on('wip_working');

            // แก้ไขให้ Foreign Key อ้างอิงคอลัมน์ที่ถูกต้อง
            $table->unsignedBigInteger('brd_brandlist_id')->nullable();
            $table->foreign('brd_brandlist_id')->references('bl_id')->on('brandlist'); // ถ้า brandlist ใช้ id ก็ไม่ต้องแก้ไข

            $table->string('brd_backboard_no')->nullable();

            $table->unsignedBigInteger('brd_eg_id')->nullable();
            $table->foreign('brd_eg_id')->references('eio_id')->on('emp_in_outs'); // ถ้า emp_in_outs ใช้ id ก็ไม่ต้องแก้ไข

            $table->string('brd_empdate_index_key')->nullable();
            $table->string('brd_lot')->nullable();
            $table->integer('brd_amount')->nullable();
            $table->text('brd_remark')->nullable();
            $table->string('brd_checker')->nullable();
            $table->string('brd_color')->nullable();
            $table->dateTime('brd_outfg_date')->nullable();
            $table->string('brd_status')->nullable();
            $table->integer('brd_count')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('brands');
    }
};
