<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bodegas extends Model
{
  protected $connection = 'sqlsrv';
  public $table = 'SOCOCO.BODEGA';
}
