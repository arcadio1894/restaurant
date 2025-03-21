<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nombre único del cupón
            $table->text('description')->nullable(); // Descripción del cupón
            $table->text('amount')->nullable(); // Monto de descuentos del cupón
            $table->text('percentage')->nullable(); // Porcentaja del cupón
            $table->enum('status', ['active', 'inactive'])->default('active'); // Estado
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
        Schema::dropIfExists('coupons');
    }
}
