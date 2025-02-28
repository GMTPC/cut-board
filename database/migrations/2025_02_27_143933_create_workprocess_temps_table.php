<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkprocessTempsTable extends Migration
{
    public function up()
    {
        Schema::create('workprocess_temps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workprocess_id'); // เก็บ ID ของ workprocess_qc
            $table->string('line'); // เก็บ Line ที่เกี่ยวข้อง
            $table->unsignedBigInteger('wwt_id'); // เก็บ wwt_id ที่เกี่ยวข้อง
            $table->timestamps();

            // 🔹 กำหนด Foreign Key (ถ้าต้องการ)
            $table->foreign('workprocess_id')->references('id')->on('workprocess_qc')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('workprocess_temps');
    }
}
