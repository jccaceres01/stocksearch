<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articulos;

class SearchController extends Controller
{
  /**
   * Return search view
   */
  public function search() {
    return view('search');
  }

  /**
   * Return result view
   */
  public function result(Request $request) {
    $art = Articulos::where('DESCRIPCION', 'like', '%'.$request->criteria.'%')
      ->paginate(10);

    return view('result')->with('articulos', $art);
  }

  /**
   * Retornar vista con rotacion de inventario de articulo entre fechas
   */
  public function rotation(Request $request) {
    if ($request->has('start_date') || $request->has('end_date')
      || $request->has('code')) {

        $startDate = new \DateTime($request->start_date);
        $endDate = new \DateTime($request->end_date);

        $art = Articulos::find($request->code);
        $transactions = $art->transaccionInv()
          ->whereBetween(\DB::raw('CONVERT(DATE, FECHA_HORA_TRANSAC)'), [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
          ])->orderBy('FECHA_HORA_TRANSAC')->get();

        return view('rotation')->with([
          'articulo' => $art,
          'transacciones' => $transactions
        ]);
    }
  }
}
