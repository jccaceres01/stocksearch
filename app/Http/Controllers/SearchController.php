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
}
