<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEioOutputAmountToEmpInOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emp_in_outs', function (Blueprint $table) {
            $table->decimal('eio_output_amount', 10, 2)->nullable()->after('eio_input_amount');
        });
    }
    
    public function down()
    {
        Schema::table('emp_in_outs', function (Blueprint $table) {
            $table->dropColumn('eio_output_amount');
        });
    }
    
}
