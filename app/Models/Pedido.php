<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
  protected $connection = 'mysql';
  protected $table = 'pedido';

  protected $fillable = [
    'numero_interno',
    'numero_salida',
    'user_id',
    'usuario_autorizacion',
    'entregado_por',
    'recivido_por',
    'estado'
  ];

  /**
   * public static estado accesor
   */
  public static $estado = ['espera', 'autorizado', 'entregado', 'expirado'];

  /**
   * pedido_detalle relationship
   */
  public function detalle() {
    return $this->hasMany('App\Models\PedidoDetalle', 'pedido_id', 'id');
  }

  /**
   * User inverse relationship
   */
  public function usuario() {
    return $this->belongsTo('App\Models\User', 'user_id', 'id');
  }

  /**
   * User inverse relationship (Usuario de aprovacion)
   */
  public function usuarioAutorizacion() {
    return $this->belongsTo('App\Models\User', 'usuario_autorizacion', 'id');
  }

  /**
   * User inverse relationship (Usuario de entrega)
   */
  public function usuarioEntrega() {
    return $this->belongsTo('App\Models\User', 'entregado_por', 'id');
  }

  /**
   * User inverse relationship (Usuario de entrega)
   */
  public function usuarioRecepcion() {
    return $this->belongsTo('App\Models\User', 'recivido_por', 'id');
  }


  /**
   * Entregados query scope
   */
  public function scopeEntregados($query) {
    return $query->where('estado', 'entregado');
  }

  /**
   * Search query scope
   */
  public function scopeSearch($query, $criteria) {
    if ($criteria != null) {
      return $query->where('numero_interno', 'like', '%'.$criteria.'%');
    }
  }

  /**
   * Get delivery day
   */
  public function getDiaEntregaAttribute() {
    $date = new \DateTime($this->attributes['fecha_entrega']);
    return $date->format('d');
  }

  /**
   * Get delivery month
   */
  public function getMesEntregaAttribute() {
    $date = new \DateTime($this->attributes['fecha_entrega']);
    return $date->format('m');
  }

  /**
   * Get delivery year
   */
  public function getAnoEntregaAttribute() {
    $date = new \DateTime($this->attributes['fecha_entrega']);
    return $date->format('Y');
  }
}
