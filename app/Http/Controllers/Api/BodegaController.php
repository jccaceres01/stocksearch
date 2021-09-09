<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BodegaController extends Controller
{
  /**
   * Get All Storage
   */
  public function index() {
    return \DB::connection('sqlsrv')->table('SOCOCO.BODEGA')->get();
  }

  /**
   * Get a specific storage
   */
  public function getStorage($warehouse) {
    return \DB::connection('sqlsrv')->table('SOCOCO.BODEGA')
      ->where('BODEGA', $warehouse)->get();
  }
}
