<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable(); // Permitimos null inicialmente
            $table->decimal('discount_amount', 9, 2)->nullable();

            // Relación con usuarios
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            // Relación con cupones
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
            // Relación con pedidos (orders)
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');

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
        Schema::dropIfExists('user_coupons');
    }
}
