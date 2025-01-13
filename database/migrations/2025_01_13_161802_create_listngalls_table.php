<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListngallsTable extends Migration  // ✅ ต้องเติม 's' ให้ตรงกับชื่อไฟล์
{
    public function up()
    {
        Schema::create('listngalls', function (Blueprint $table) {  // ✅ ชื่อตารางต้องตรงกัน
            $table->bigIncrements('lng_id');
            $table->string('lng_name');
            $table->integer('lng_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('listngalls');
    }
}

