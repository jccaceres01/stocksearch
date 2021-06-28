<?php

use Illuminate\Database\Seeder;
use App\Carrito;
use App\ExistenciaLotes;
use App\User;

class CarritoTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    foreach (User::all() as $user) {
      for ($i=0; $i < rand(2, 5); $i++) {
        $existencia = ExistenciaLotes::inRandomOrder()
          ->where('CANT_DISPONIBLE', '>', 0)->first();

        $original = ($existencia->articulo->CLASIFICACION_2 == '02-01')
          ? true
          : false;

        Carrito::create([
          'articulo_id' => $existencia->ARTICULO,
          'bodega_id' => $existencia->BODEGA,
          'localizacion' => $existencia->LOCALIZACION,
          'original' => $original,
          'cantidad' => rand(1, $existencia->CANT_DISPONIBLE),
          'user_id' => $user->id
        ]);
      }
    }
  }
}
