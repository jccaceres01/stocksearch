<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CostCenterController extends Controller
{
    /**
     * Get Cost Center Resources
     */
    public function index() {
        return \DB::connection('sqlsrv')->table('SOCOCO.CENTRO_COSTO')
            ->where('ACEPTA_DATOS', 'S')
            ->get();
    }
}
