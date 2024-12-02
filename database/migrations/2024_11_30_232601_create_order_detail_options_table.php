<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_detail_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_detail_id')->constrained()->onDelete('cascade'); // Relación con detalles del pedido
            $table->foreignId('option_id')->constrained()->onDelete('cascade');       // Relación con la opción seleccionada
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade'); // Producto seleccionado (puede ser nulo)
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
        Schema::dropIfExists('order_detail_options');
    }
}
