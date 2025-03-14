<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wipziptape1', function (Blueprint $table) {
            $table->id('wz_id');
            $table->string('wz_line');
            $table->unsignedBigInteger('wz_worktime_id');
            $table->decimal('wz_amount', 10, 3);
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
        Schema::dropIfExists('wipziptape1');
    }
};

