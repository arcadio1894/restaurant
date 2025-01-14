<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateFinishToProductDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_days', function (Blueprint $table) {
            $table->date('date_finish')->nullable()->after('day')->comment('Fecha hasta la cual es válido el registro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_days', function (Blueprint $table) {
            $table->dropColumn('date_finish');
        });
    }
}
