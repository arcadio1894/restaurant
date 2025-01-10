<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailToppingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_detail_toppings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_detail_id')->nullable()->constrained('order_details');
            $table->foreignId('topping_id')->nullable()->constrained('toppings');
            $table->string('topping_name')->nullable();
            $table->string('type')->nullable();
            $table->boolean('extra')->nullable();
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
        Schema::dropIfExists('order_detail_toppings');
    }
}
