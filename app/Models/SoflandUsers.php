<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoflandUsers extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'ERPADMIN.USUARIO';
    protected $keyType = 'varchar';
    public $incrementing = false;

    /**
     * Get repuestos user
     */
    public function user() {
        return $this->belongsTo('App\Models\User', 'email', 
            'CORREO_ELECTRONICO');
    }
}
