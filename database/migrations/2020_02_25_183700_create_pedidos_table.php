<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::connection('mysql')
      ->create('pedido', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('numero_interno', 6);
        $table->string('numero_salida', )->nullable();
        $table->bigInteger('user_id');
        $table->bigInteger('usuario_autorizacion')->nullable()->default(null);
        $table->bigInteger('entregado_por')->nullable()->default(null);
        $table->bigInteger('recivido_por')->nullable()->default(null);
        $table->enum('estado', ['espera', 'autorizado',
          'entregado', 'expirado'])
          ->nullable()->default('espera'); // despachador de almacen
        $table->datetime('fecha_entrega')->nullable()->default(null);
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
    Schema::connection('mysql')->dropIfExists('pedido');
  }
}
