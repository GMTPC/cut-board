<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupqcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groupQC', function (Blueprint $table) {
            $table->id();
            $table->string('group'); // ชื่อกลุ่ม
            $table->string('line');  // ไลน์
            $table->date('date');    // วันที่
            $table->timestamps();    // created_at และ updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groupQC');
    }
}
