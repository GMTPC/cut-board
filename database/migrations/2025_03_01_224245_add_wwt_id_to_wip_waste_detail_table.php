<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('wip_waste_detail', function (Blueprint $table) {
            $table->bigIncrements('wwd_id'); // Primary Key
            $table->integer('wwd_line')->nullable();
            $table->integer('wwd_index')->nullable();
            $table->string('wwd_barcode', 255)->nullable();
            $table->string('wwd_lot', 255)->nullable();
            $table->integer('wwd_amount')->nullable();
            $table->dateTime('wwd_date')->nullable();
            $table->bigInteger('wwt_id')->unsigned()->nullable(); // Foreign Key
            $table->timestamps();

            // 🔹 กำหนด Foreign Key (เชื่อมกับ wip_worktime)
            $table->foreign('wwt_id')->references('wwt_id')->on('wip_worktimes')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wip_waste_detail');
    }
};

