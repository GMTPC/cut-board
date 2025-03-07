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
            $table->id('cswi_id');
            $table->string('cswi_index')->unique();
            $table->string('cswi_ziptape')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('check_csv_wh_index');
    }
};
