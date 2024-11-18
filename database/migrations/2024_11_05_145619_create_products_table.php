<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->text('full_name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('stock_current', 6,2)->default(0);
            $table->decimal('unit_price', 9,2)->nullable()->default(0);
            $table->string('image')->nullable();
            $table->foreignId('category_id')->constrained('categories');
            $table->boolean('enable_status')->default(1);
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
        Schema::dropIfExists('products');
    }
}
