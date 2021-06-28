<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clasificaciones extends Model
{
  protected $conection = 'sqlsrv';
  protected $table = 'SOCOCO.CLASIFICACION';
  public $incrementing = false;
  public $keyType = 'varchar';
  protected $primary = 'CLASIFICACION';
}
