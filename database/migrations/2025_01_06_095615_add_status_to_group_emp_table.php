<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToGroupEmpTable extends Migration
{
    public function up()
    {
        Schema::table('group_emp', function (Blueprint $table) {
            $table->boolean('status')->default(0)->after('date'); // เพิ่มคอลัมน์ status
        });
    }

    public function down()
    {
        Schema::table('group_emp', function (Blueprint $table) {
            $table->dropColumn('status'); // ลบคอลัมน์ status หาก rollback
        });
    }
}
