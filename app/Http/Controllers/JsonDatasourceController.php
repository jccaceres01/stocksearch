<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;

class JsonDatasourceController extends Controller
{
  /**
   * JSON source for print pedidos
   */
  public function pedidosJson($pedido_id) {
    $pedido = Pedido::find($pedido_id)->with([
        'detalle.articulo',
        'usuario',
        'usuarioAutorizacion',
        'usuarioEntrega',
        'usuarioRecepcion'
    ])->where('id', $pedido_id)->first();

    return response()->json($pedido);
  }
}
