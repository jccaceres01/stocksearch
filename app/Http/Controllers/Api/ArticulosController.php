<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Articulos;

class ArticulosController extends Controller
{
    /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return response()->json(Articulos::select(
      'ARTICULO',
      'DESCRIPCION',
      'CLASIFICACION_2',
      'CODIGO_BARRAS_VENT',
      'CODIGO_BARRAS_INVT'
    )->get(), 200);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    return response()->json(Articulos::create($request->all()), 200);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    return esponse()->json(Articulos::find($id), 200);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $art = Articulos::findOrFail($id);
    return response()->json($art->update($request->all()), 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    return response()->json(Articulos::destroy($id), 200);
  }

  /**
   * Custom search
   */
  public function customSearch(Request $request) {
    return response()->json(Articulos::where('DESCRIPCION', 'like', '%'.$request->criteria.'%')
      ->orWhere('ARTICULO', 'like', '%'.$request->criteria.'%')
      ->take(200)->get(), 200);
  }
  /**
   * Search by Description or Barcode
   */
  public function customSearchWithBarcode(Request $request) {

    try {
      return \DB::connection('sqlsrv')->table('SOCOCO.EXISTENCIA_LOTE')
        ->join('SOCOCO.ARTICULO', 'SOCOCO.EXISTENCIA_LOTE.ARTICULO',
          '=', 'SOCOCO.ARTICULO.ARTICULO')
        ->select(
          'SOCOCO.EXISTENCIA_LOTE.BODEGA',
          'SOCOCO.EXISTENCIA_LOTE.ARTICULO',
          'SOCOCO.ARTICULO.DESCRIPCION',
          'SOCOCO.EXISTENCIA_LOTE.LOCALIZACION',
          'SOCOCO.ARTICULO.CLASIFICACION_2',
          'SOCOCO.EXISTENCIA_LOTE.CANT_DISPONIBLE',
          'SOCOCO.EXISTENCIA_LOTE.CANT_RESERVADA',
          'SOCOCO.ARTICULO.CODIGO_BARRAS_VENT',
          'SOCOCO.ARTICULO.CODIGO_BARRAS_INVT'
        )->where('ARTICULO.DESCRIPCION', 'like',
          '%'.$request->criteria.'%')
        ->orWhere('EXISTENCIA_LOTE.ARTICULO', 'like',
          '%'.$request->criteria.'%')
        ->orWhere('ARTICULO.CODIGO_BARRAS_VENT', 'like',
          '%'.$request->criteria.'%')
        ->orWhere('ARTICULO.CODIGO_BARRAS_INVT', 'like',
          '%'.$request->criteria.'%')
        ->get();
    } catch (\Exception $e) {
      switch ($e->getCode()) {
        default:
          info($e);
          abort(500);
      }
    }
  }

  /**
   * Get the stock of a item in lote
   */
  public function loteStockLevel(Request $request) {
    return $loteStockLevel = \DB::connection('sqlsrv')
      ->table('SOCOCO.EXISTENCIA_LOTE')
      ->where('ARTICULO', $request->articulo)
      ->orderBy('BODEGA', 'ASC')
      ->orderBy('LOCALIZACION', 'ASC')
      ->get();
  }
}
