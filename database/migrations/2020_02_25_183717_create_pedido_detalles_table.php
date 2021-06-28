<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoDetallesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::connection('mysql')
      ->create('pedido_detalle', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->bigInteger('pedido_id');
        $table->string('articulo_id', 20);
        $table->string('bodega_id', 4);
        $table->string('localizacion', 8);
        $table->float('cantidad');
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
    Schema::connection('mysql')->dropIfExists('pedido_detalle');
  }
}
