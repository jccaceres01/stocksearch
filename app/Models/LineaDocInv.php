<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineaDocInv extends Model
{
  protected $connection = 'sqlsrv';
  protected $table = 'SOCOCO.LINEA_DOC_INV';
  public $incrementing = false;
  public $timestamps = false;
}
