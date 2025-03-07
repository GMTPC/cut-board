<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseReturnToQcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('warehouse_return_to_qc', function (Blueprint $table) {
            $table->id('wrtc_id'); // Primary Key
            $table->string('wrtc_barcode')->unique();
            $table->text('wrtc_description')->nullable();
            $table->text('wrtc_remark')->nullable();
            $table->date('wrtc_date')->default(now());
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down() {
        Schema::dropIfExists('warehouse_return_to_qc');
    }
};