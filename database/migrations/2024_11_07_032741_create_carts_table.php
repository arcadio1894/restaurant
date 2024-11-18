<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *  pending: El carrito está activo, y el cliente está agregando productos.
        completed: El carrito ha sido pagado y finalizado.
        canceled: El cliente o el sistema canceló el carrito.
        abandoned (opcional): El cliente abandonó el carrito sin completar la compra, útil para seguimiento de carritos abandonados.
        processing (opcional): La orden del carrito está en proceso de pago o preparación.
        failed (opcional): Hubo un error en el pago, y la transacción no se completó.
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'completed', 'canceled', 'abandoned', 'processing', 'failed'])->default('pending');
            $table->decimal('total', 10, 2)->default(0);
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
        Schema::dropIfExists('carts');
    }
}
