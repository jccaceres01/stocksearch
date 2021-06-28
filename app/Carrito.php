<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
  protected $connection = 'mysql';
  protected $table = 'carrito';

  protected $fillable = [
    'articulo_id',
    'bodega_id',
    'localizacion',
    'original',
    'cantidad',
    'user_id'
  ];

  /**
   * Override carrito model events
   */
  public static function boot() {
    parent::boot();

    static::creating(function($carrito) {
      if (auth()->check()) {
        return $carrito->user_id = auth()->id();
      }
    });
  }

  /**
   * Carrito-Articulo relationship
   */
  public function articulo() {
    return $this->hasOne('App\Articulos', 'ARTICULO', 'articulo_id');
  }

  /**
   * Carrito - Bodega relationship
   */
  public function bodega() {
    return $this->hasOne('App\Bodegas', 'BODEGA', 'bodega_id');
  }
}
