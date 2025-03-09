<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable()->unique();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            // Usar utf8mb3 para que coincida con districts
            $table->string('department_id', 2)->nullable()->charset('utf8mb3')->collation('utf8mb3_general_ci');
            $table->string('province_id', 4)->nullable()->charset('utf8mb3')->collation('utf8mb3_general_ci');
            $table->string('district_id', 6)->nullable()->charset('utf8mb3')->collation('utf8mb3_general_ci');

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('type', ['principal', 'sucursal'])->default('principal');
            $table->timestamps();

            // Clave foránea si hay usuarios dueños de tiendas
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
