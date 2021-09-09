<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\support\Str;

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
      'email' => 'jcaceres@socococr.com',
      'password' => \Hash::make('Password1'),
      'remember_token' => Str::random(32)
    ]);
  }
}
