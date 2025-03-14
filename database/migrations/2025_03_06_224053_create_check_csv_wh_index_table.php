<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckCsvWhIndexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('check_csv_wh_index', function (Blueprint $table) {
            $table->id('cswi_id');  // Primary Key
            $table->string('cswi_index')->unique(); // ตัวอ้างอิงเชื่อมโยงกับ `ccw_index`
            $table->string('cswi_ziptape')->nullable(); // เพิ่มฟิลด์อื่นๆ
            $table->timestamps();  // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('check_csv_wh_index');
    }
}
