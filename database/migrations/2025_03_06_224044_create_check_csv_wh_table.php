<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckCsvWhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('check_csv_wh', function (Blueprint $table) {
            $table->id('ccw_id');
            $table->foreignId('ccw_index')->nullable()->constrained('check_csv_wh_index');
            $table->string('ccw_barcode')->nullable();
            $table->string('ccw_lot')->nullable();
            $table->integer('ccw_amount')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('check_csv_wh');
    }
};
