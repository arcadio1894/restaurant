<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartDetailOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_detail_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_detail_id')->constrained()->onDelete('cascade'); // Relación con cart_details
            $table->foreignId('option_id')->constrained()->onDelete('cascade');     // Relación con la tabla options
            $table->foreignId('product_id')->constrained()->onDelete('cascade');   // Producto seleccionado como opción
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
        Schema::dropIfExists('cart_detail_options');
    }
}
