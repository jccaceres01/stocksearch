<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulos extends Model
{
  protected $table = 'SOCOCO.ARTICULO';

  public $incrementing = false;
  protected $keyType = 'varchar';
  protected $primaryKey = 'ARTICULO';

  /**
   * Get part number of the description
   */
  public function getPartNumberAttribute() {
    return trim(substr($this->attributes['DESCRIPCION'], 0,
      strpos($this->attributes['DESCRIPCION'], '|')));
  }

  /**
   * Get the part name of the description
   */
  public function getPartNameAttribute() {
    return trim(substr($this->attributes['DESCRIPCION'],
      strpos($this->attributes['DESCRIPCION'], '|')+1,
      strlen($this->attributes['DESCRIPCION'])));
  }

  /**
   * Existencia_bodega relationship
   */
  public function existenciaBodega() {
    return $this->hasMany('App\ExistenciaBodegas', 'ARTICULO', 'ARTICULO');
  }

  /**
   * ECISTENCIA_LOTE foreign key relationship
   */
  public function existenciaLote() {
    return $this->hasMany('App\ExistenciaLotes', 'ARTICULO', 'ARTICULO');
  }

  /**
   * Articulos alternos belongsToMany relacion
   */
  public function alternos() {
    return $this->belongsToMany('App\Articulos', 'SOCOCO.ARTICULO_ALTERNO',
      'ARTICULO', 'ALTERNO');
  }

  /**
   * Original/Aftermaker Clasificacion relationship
   */
  public function original() {
    return $this->hasOne('App\Clasificaciones', 'CLASIFICACION',
      'CLASIFICACION_2');
  }
  /**
   * Search Query Scope
   */
  // public function scopeSearch($query, $criteria) {
  //   if ($criteria != null) {
  //     return $query->where('ARTICULO', 'like', '%'.$criteria.'%')
  //       ->orWhere('DESCRIPCION', 'like', '%'.$criteria.'%');
  //   }
  // }
}
