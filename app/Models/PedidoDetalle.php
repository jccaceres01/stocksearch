<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
  protected $connection = 'mysql';
  protected $table = 'pedido_detalle';

  protected $fillable = [
    'pedido_id',
    'articulo_id',
    'bodega_id',
    'localizacion',
    'cantidad'
  ];

  /**
   * Pedido_Detalle - Articulo relationship
   */
  public function articulo() {
    return $this->hasOne('App\Models\Articulos', 'ARTICULO', 'articulo_id');
  }
}
