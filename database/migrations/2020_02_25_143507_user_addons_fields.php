<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserAddonsFields extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::connection('mysql')->table('users', function(Blueprint $table) {
      $table->string('username', 45)->after('email')->nullable();
      $table->string('api_token', 80)->after('password')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::connection('mysql')->table('users', function(Blueprint $table) {
      $table->dropColumn('username');
      $table->dropColumn('api_token');
    });
  }
}
