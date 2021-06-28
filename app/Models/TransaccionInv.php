<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaccionInv extends Model
{
  public $connection = 'sqlsrv';

  protected $table = 'SOCOCO.TRANSACCION_INV';

  /**
   * Audit_trans_inv relacion 1-1
   */
  public function auditTransInv() {
    return $this->belongsTo('App\Models\AuditTransInv', 'AUDIT_TRANS_INV', 'AUDIT_TRANS_INV');
  }
}
