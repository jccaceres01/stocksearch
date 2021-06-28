<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PedidoDetalle;
use App\Models\Pedido;
use App\Models\ExistenciaLotes;

class PedidoDetalleTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    foreach (Pedido::all() as $pedido) {
      for ($i=0; $i < rand(2, 5); $i++) {

        $existenciaLote = ExistenciaLotes::inRandomOrder()
          ->where('CANT_DISPONIBLE', '>', 0)->first();

        PedidoDetalle::create([
          'pedido_id' => $pedido->id,
          'articulo_id' => $existenciaLote->ARTICULO,
          'bodega_id' => $existenciaLote->BODEGA,
          'localizacion' => $existenciaLote->LOCALIZACION,
          'cantidad' => rand(1, $existenciaLote->CANT_DISPONIBLE)
        ]);
      }
    }
  }
}
