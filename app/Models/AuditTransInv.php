<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditTransInv extends Model
{
  protected $connection = 'sqlsrv';

  protected $table = 'SOCOCO.AUDIT_TRANS_INV';

  /**
   * Transaccion_Inv relacion 1:m
   */
  public function transaccionInv() {
    return $this->hasMany('App\Models\TransaccionInv',
      'AUDIT_TRANS_INV', 'AUDIT_TRANS_INV');
  }
}
