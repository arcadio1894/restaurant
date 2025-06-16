<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprobantesReclamacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobantes_reclamaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reclamacion_id');
            $table->string('archivo'); // nombre del archivo o ruta
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('reclamacion_id')->references('id')->on('reclamaciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comprobantes_reclamaciones');
    }
}
