<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CarritoRequest;

use App\Models\User;
use App\Models\Carrito;

class CarritoController extends Controller
{
  /**
   * Constructor
   */
  public function __construct() {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    try {
      return view('carrito.index')->with('items',
        auth()->user()->carrito()->get());
    } catch (\Exception $e) {
      switch ($e->getCode()) {
        default:
          info($e);
          toastr()->error($e->getMessage(), 'Error: ');
          return back();
      }
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {

  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(CarritoRequest $request)
  {
    try {
      $existsInCart = Carrito::where('articulo_id', $request->get('articulo_id'))
        ->where('bodega_id', $request->get('bodega_id'))
        ->where('localizacion', $request->get('localizacion'))
        ->where('original', $request->original)
        ->where('user_id', auth()->id())->first();

      if ($existsInCart !== null) {
        $request->request->add(['adding-qty' => 1]);
        return $this->update($request, $existsInCart);
      } else {
        Carrito::create($request->all());
        toastr()->success('Repuesto Agregado');
        return redirect()->route('carrito.index');
      }
    } catch (\Exception $e) {
      switch ($e->getCode()) {
        default:
          info($e);
          toastr()->error($e->getMessage(), 'Error: ');
          return back()->WithInput();
      }
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Carrito  $carrito
   * @return \Illuminate\Http\Response
   */
  public function show(Carrito $carrito)
  {

  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Carrito  $carrito
   * @return \Illuminate\Http\Response
   */
  public function edit(Carrito $carrito)
  {
      //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Carrito  $carrito
   * @return \Illuminate\Http\Response
   */
  public function update(CarritoRequest $request, Carrito $carrito)
  {

    try {

      if ($request->has('adding-qty')) {
        $carrito->cantidad += $request->get('cantidad');
        $carrito->save();
        toastr()->success('Cantidad Agregada');
        return redirect()->route('carrito.index');
      } else {
        $carrito->update($request->all());
        toastr()->success('Carrito actualizado');
        return redirect()->route('carrito.index');
      }
    } catch (\Exception $e) {
      switch ($e->getCode()) {
        default:
          info($e);
          toastr()->error($e->getMessage(), 'Error: ');
          return back()->WithInput();
      }
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Carrito  $carrito
   * @return \Illuminate\Http\Response
   */
  public function destroy(Carrito $carrito)
  {
    try {
      Carrito::destroy($carrito->id);
      toastr()->success('Registro Borrado');
      return redirect()->route('carrito.index');
    } catch (\Exception $e) {
      switch ($e->getCode()) {
        default:
          info($e);
          toastr()->error($e->getMessage(), 'Error: ');
          return back()->WithInput();
      }
    }
  }
}
