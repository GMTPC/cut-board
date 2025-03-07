<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('production_colors', function (Blueprint $table) {
            $table->id('pcs_id');
            $table->string('pcs_color');
            $table->text('pcs_remark')->nullable();
            $table->date('pcs_date')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('production_colors');
    }
};