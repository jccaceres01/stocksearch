<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulos extends Model
{
  protected $table = 'SOCOCO.ARTICULO';

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
