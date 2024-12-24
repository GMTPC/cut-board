<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');             // ชื่อพนักงาน
            $table->string('note')->nullable(); // หมายเหตุ (ไม่จำเป็นต้องกรอก)
            $table->string('group')->nullable(); // กลุ่มพนักงาน (เช่น Group A, B)
            $table->string('line')->nullable();  // ไลน์พนักงาน (เช่น Line 1, 2)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
