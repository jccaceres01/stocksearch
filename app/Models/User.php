<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'username', 'password', 'api_token',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  /**
   * Carrito-user relationship
   */
  public function carrito() {
    return $this->hasMany('App\Models\Carrito', 'user_id', 'id');
  }

  /**
   * Pedido - User relationship
   */
  public function pedidos() {
    return $this->hasMany('App\Models\Pedido');
  }

  /**
   * Get ERPADMIN Softland User
   */
  public function softlandUser() {
    return $this->hasOne('App\Models\SoflandUsers',
      'CORREO_ELECTRONICO', 'email');
  }
}
