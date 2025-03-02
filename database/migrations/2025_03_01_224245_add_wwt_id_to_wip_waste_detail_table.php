<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('wip_waste_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('wwt_id')->nullable()->after('wwd_id'); // ✅ เพิ่มคอลัมน์ wwt_id
            $table->foreign('wwt_id')->references('wwt_id')->on('wip_worktimes')->onDelete('cascade'); // ✅ เปลี่ยน Foreign Key ให้เชื่อมกับ wip_worktime
        });
    }

    public function down()
    {
        Schema::table('wip_waste_detail', function (Blueprint $table) {
            $table->dropForeign(['wwt_id']); // ลบ Foreign Key
            $table->dropColumn('wwt_id'); // ลบคอลัมน์
        });
    }
};

