<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarritoTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::connection('mysql')
      ->create('carrito', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('articulo_id', 20);
        $table->string('bodega_id', 4);
        $table->string('localizacion', 8);
        $table->boolean('original')->nullable();
        $table->float('cantidad');
        $table->bigInteger('user_id')->nullable();
        $table->unique(['articulo_id', 'bodega_id',
          'localizacion', 'original', 'user_id'], 'carrito_unico');
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
    Schema::connection('mysql')->dropIfExists('carrito');
  }
}
