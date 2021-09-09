<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocalizacionController extends Controller
{
  /**
   * Get all locations
   */
  public function index() {
    return \DB::connection('sqlsrv')->table('SOCOCO.LOCALIZACION')->get();
  }

  /**
   * Get a specific location
   */
  public function getSpecificLocation($warehouse, $location) {
    return \DB::connection('sqlsrv')->table('SOCOCO.LOCALIZACION')
      ->where('BODEGA', $warehouse)->where('LOCALIZACION', $location)->get();
  }

  /**
   * Get Locations from a storage
   */
  public function getLocationFromStorage($warehouse) {
    return \DB::connection('sqlsrv')->table('SOCOCO.LOCALIZACION')
      ->where('BODEGA', $warehouse)
      ->orderBy('BODEGA', 'DESC')
      ->get();
  }
}
