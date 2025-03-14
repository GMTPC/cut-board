<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWipWorktimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('wip_worktimes', function (Blueprint $table) {
        $table->id('wwt_id'); // Primary Key
        $table->string('wwt_index')->unique(); // เพิ่มให้ตรงกับ Model
        $table->string('wwt_status')->nullable();
        $table->string('wwt_line', 10);
        $table->date('wwt_date')->nullable();
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
        Schema::dropIfExists('wip_worktimes');
    }
}
