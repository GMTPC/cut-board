<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWipSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wip_summary', function (Blueprint $table) {
            $table->bigIncrements('ws_id'); // เปลี่ยนเป็น ws_id เป็น Primary Key
            $table->decimal('ws_output_amount', 10, 2);
            $table->decimal('ws_input_amount', 10, 2);
            $table->integer('ws_working_id');
            $table->decimal('ws_holding_amount', 10, 2);
            $table->decimal('ws_ng_amount', 10, 2);
            $table->integer('ws_index');
            $table->timestamps(); // Automatically adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wip_summary'); // เปลี่ยนเป็น 'wip_summary'
    }
}
