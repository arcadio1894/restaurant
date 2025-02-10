<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReclamacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reclamaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();  // Código único para identificar la reclamación
            $table->string('nombre');
            $table->string('apellido');
            $table->string('tipo_documento');
            $table->string('numero_documento');
            $table->string('telefono');
            $table->string('email');
            $table->string('departamento');
            $table->string('provincia');
            $table->string('distrito');
            $table->string('direccion');
            $table->boolean('menor_edad');
            $table->string('nombre_representante')->nullable();
            $table->string('telefono_representante')->nullable();
            $table->string('direccion_representante')->nullable();
            $table->string('correo_representante')->nullable();
            $table->string('tipo_bien');
            $table->decimal('monto', 10, 2)->nullable();
            $table->text('descripcion');
            $table->string('tipo_reclamacion');
            $table->string('canal');
            $table->string('motivo');
            $table->string('submotivo');
            $table->text('detalle');
            $table->text('pedido_cliente');
            $table->string('comprobante')->nullable();
            $table->enum('estado', ['pendiente', 'revisado', 'solucionado', 'anulado'])->default('pendiente');
            $table->text('respuesta')->nullable();
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
        Schema::dropIfExists('reclamaciones');
    }
}
