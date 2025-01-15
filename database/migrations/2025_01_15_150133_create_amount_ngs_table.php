<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmountNgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amount_ngs', function (Blueprint $table) {
            $table->bigIncrements('amg_id'); // Primary Key
            $table->unsignedBigInteger('amg_wip_id'); // Foreign Key to wipbarcodes
            $table->unsignedBigInteger('amg_ng_id');  // Foreign Key to listngalls
            $table->integer('amg_amount');  // Amount of NG
            $table->timestamps(); // created_at and updated_at

            // ✅ แก้ไข Foreign Key ให้ถูกต้อง
            $table->foreign('amg_wip_id')
                  ->references('wip_id')
                  ->on('wipbarcodes')  // เปลี่ยนจาก wipbarcode ➔ wipbarcodes
                  ->onDelete('cascade');

            $table->foreign('amg_ng_id')
                  ->references('lng_id')
                  ->on('listngalls');  // เปลี่ยนจาก list_ng ➔ listngalls
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amount_ngs');
    }
}
