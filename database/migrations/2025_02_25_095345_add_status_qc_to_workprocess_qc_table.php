<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusQcToWorkprocessQcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workprocess_qc', function (Blueprint $table) {
            $table->string('status_qc')->nullable()->after('status');
        });
    }
    
    public function down()
    {
        Schema::table('workprocess_qc', function (Blueprint $table) {
            $table->dropColumn('status_qc');
        });
    }
    
}
