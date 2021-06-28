<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bodegas extends Model
{
  protected $connection = 'sqlsrv';
  public $table = 'SOCOCO.BODEGA';
}
