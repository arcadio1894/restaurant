<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_districts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nombre del distrito
            $table->decimal('shipping_cost', 10, 2); // Costo de envío
            $table->string('ubigeo')->unique(); // Ubigeo del distrito
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
        Schema::dropIfExists('shipping_districts');
    }
}
