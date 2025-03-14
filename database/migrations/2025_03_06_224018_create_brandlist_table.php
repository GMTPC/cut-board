<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandlistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('brandlist', function (Blueprint $table) {
            $table->id('bl_id');
            $table->string('bl_name');
            $table->string('bl_code')->unique();
            $table->string('bl_status')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('brandlist');
    }
};
