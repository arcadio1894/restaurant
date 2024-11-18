<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del método de pago (e.g., "Tarjeta de crédito", "PayPal", etc.)
            $table->string('code')->unique(); // Código único (e.g., "credit_card", "paypal", "cash")
            $table->text('description')->nullable(); // Descripción del método de pago
            $table->boolean('is_active')->default(true); // Si el método está activo o no
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
        Schema::dropIfExists('payment_methods');
    }
}
