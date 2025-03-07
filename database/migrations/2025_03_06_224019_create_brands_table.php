<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('brands', function (Blueprint $table) {
            $table->id('brd_id');
            $table->foreignId('brd_working_id')->nullable()->constrained('wip_workings');
            $table->foreignId('brd_brandlist_id')->nullable()->constrained('brandlist');
            $table->string('brd_backboard_no')->nullable();
            $table->foreignId('brd_eg_id')->nullable()->constrained('emp_in_outs');
            $table->string('brd_empdate_index_key')->nullable();
            $table->string('brd_lot')->nullable();
            $table->integer('brd_amount')->nullable();
            $table->text('brd_remark')->nullable();
            $table->string('brd_checker')->nullable();
            $table->string('brd_color')->nullable();
            $table->dateTime('brd_outfg_date')->nullable();
            $table->string('brd_status')->nullable();
            $table->integer('brd_count')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('brands');
    }
};
