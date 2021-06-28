<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExistenciaBodegas extends Model
{
  protected $connection = 'sqlsrv';
  protected $table = 'SOCOCO.EXISTENCIA_BODEGA';

}
