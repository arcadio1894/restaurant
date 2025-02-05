<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSunatFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('serie', 10)->nullable();
            $table->integer('numero')->nullable();
            $table->string('type_document', 2)->nullable();
            $table->string('sunat_ticket')->nullable();
            $table->string('sunat_status')->nullable();
            $table->text('sunat_message')->nullable();
            $table->string('xml_path')->nullable();
            $table->string('cdr_path')->nullable();
            $table->date('fecha_emision')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'serie',
                'numero',
                'type_document',
                'sunat_ticket',
                'sunat_status',
                'sunat_message',
                'xml_path',
                'cdr_path',
                'fecha_emision'
            ]);
        });
    }
}
