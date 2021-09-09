<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoInv extends Model
{
  protected $connection = 'sqlsrv';
  protected $table = 'SOCOCO.DOCUMENTO_INV';

  protected $keyType = 'varchar';
  public $incrementing = false;
  public $timestamps = false;

}
