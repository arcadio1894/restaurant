<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->unsignedTinyInteger('day'); // Días: 0 (Domingo) a 6 (Sábado)
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
        Schema::dropIfExists('product_days');
    }
}
