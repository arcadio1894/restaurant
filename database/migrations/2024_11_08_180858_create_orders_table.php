<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con el usuario
            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->onDelete('set null'); // Dirección de envío
            $table->foreignId('billing_address_id')->nullable()->constrained('addresses')->onDelete('set null'); // Dirección de facturación
            $table->decimal('total_amount', 10, 2); // Monto total del pedido
            $table->enum('status', ['created', 'processing', 'shipped', 'completed'])->default('created'); // Estado del pedido
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->onDelete('set null'); // Relación con payment_methods
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
        Schema::dropIfExists('orders');
    }
}
