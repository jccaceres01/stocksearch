<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExistenciaLotes extends Model
{
  public $connection = 'sqlsrv';

  protected $table = 'SOCOCO.EXISTENCIA_LOTE';

  public $incrementing = false;

  /**
   * Bodega foreign key relationship
   */
  public function storage() {
    return $this->hasOne('App\Bodegas', 'BODEGA', 'BODEGA');
  }

  /**
   * EXIST_LOTE - ARTICULO relationship
   */
  public function articulo() {
    return $this->hasOne('App\Articulos', 'ARTICULO', 'ARTICULO');
  }
}
