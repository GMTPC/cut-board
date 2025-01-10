<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpInOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_in_outs', function (Blueprint $table) {
            $table->id('eio_id');
            $table->unsignedBigInteger('eio_emp_group'); // กลุ่มพนักงาน
            $table->unsignedBigInteger('eio_working_id'); // เชื่อมโยงกับตาราง workprocess_qc
            $table->integer('eio_input_amount');
            $table->string('eio_line', 10);
            $table->string('eio_division', 50)->nullable();
            $table->timestamps();
    
            // กำหนด Foreign Key
            $table->foreign('eio_working_id')->references('id')->on('workprocess_qc')->onDelete('cascade');
        });
    }
    
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emp_in_outs');
    }
}
