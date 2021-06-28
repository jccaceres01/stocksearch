<?php

use Illuminate\Database\Seeder;
use App\User;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // Faker instance for fake info
    $faker = Faker::create();

    // My test user
    User::create([
      'name' => 'Julio Caceres',
      'email' => 'jcesar01@hotmail.es',
      'username' => 'jccaceres01',
      'password' => \Hash::make('Password1'),
      'api_token' => Str::random(80),
      'remember_token' => Str::random(32)
    ]);

    // More Test user
    for ($i=0; $i < rand(3, 10); $i++) {

      User::create([
        'name' => $faker->name,
        'email' => 'user'.$i.'@mail.com',
        'username' => 'user'.$i,
        'password' => \Hash::make('Password1'),
        'api_token' => Str::random(80),
        'remember_token' => Str::random(32)
      ]);

    }
  }
}
