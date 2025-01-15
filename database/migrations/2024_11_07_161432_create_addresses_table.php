<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con el usuario
            $table->enum('type', ['billing', 'shipping'])->default('shipping'); // Tipo de dirección: envío o facturación
            $table->string('phone')->nullable(); // Telefono
            $table->string('first_name')->nullable(); // Telefono
            $table->string('last_name')->nullable(); // Telefono
            $table->string('address_line')->nullable(); // Dirección (departamento, suite, etc.)
            $table->string('reference')->nullable(); // Ciudad
            $table->string('city')->nullable(); // Ciudad
            $table->string('state')->nullable(); // Estado o provincia
            $table->string('postal_code')->nullable(); // Código postal
            $table->string('country')->nullable(); // País
            $table->boolean('is_default')->default(false); // Indicador de dirección predeterminada
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
        Schema::dropIfExists('addresses');
    }
}
