<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupEmpTableV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_emp', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('emp1');  // ชื่อพนักงาน 1
            $table->string('emp2');  // ชื่อพนักงาน 2
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
        Schema::dropIfExists('group_emp_table_v1');
    }
}
