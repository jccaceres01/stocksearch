<?php

use Illuminate\Database\Seeder;
use App\Pedido;
use App\User;
use Faker\Factory as Faker;

class PedidoTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = Faker::create();

    for ($i=0; $i < 300; $i++) {

      $estado = Pedido::$estado[rand(0, sizeof(Pedido::$estado)-1)];

      Pedido::create([
        'numero_interno' => str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT)
          .'-'.str_pad(rand(1, 199), 3, '0', STR_PAD_LEFT),
        'numero_salida' => 'SAL-'.str_pad(rand(1, 100), 6, '0', STR_PAD_LEFT),
        'user_id' => User::inRandomOrder()->first()->id,
        'usuario_autorizacion'
          => ($estado == 'autorizado' || $estado == 'entregado')
          ? User::inRandomOrder()->first()->id : null,
        'entregado_por' => ($estado == 'entregado')
          ? User::inRandomOrder()->first()->id : null,
        'recivido_por' => ($estado == 'entregado')
          ? User::inRandomOrder()->first()->id : null,
        'estado' => $estado,
        'fecha_entrega' => ($estado == 'entregado')
          ? $faker->dateTimeBetween('-5 days', 'today') : null
      ]);
    }
  }
}
