<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExistenciaReservas extends Model
{
  protected $connection = 'sqlsrv';
  protected $table = 'SOCOCO.EXISTENCIA_RESERVA';

  protected $keyType = 'string';
  public $incrementing = false;
}
