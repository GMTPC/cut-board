<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTypeEmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_type_emps', function (Blueprint $table) {
            $table->id('pe_id');
            $table->unsignedBigInteger('pe_working_id');
            $table->string('pe_type_code', 10);
            $table->string('pe_type_name', 255)->nullable();
            $table->integer('pe_index')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_type_emps');
    }
}
