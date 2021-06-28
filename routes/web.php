<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  if (auth()->check()) {
    return redirect()->route('search');
  } else {
    return view('landing');
  }
});

/**
 * Search and Results Routes
 */
Route::get('search', 'SearchController@search')->name('search');
Route::get('result', 'SearchController@result')->name('result');
Route::post('rotation', 'SearchController@rotation')->name('article.rotation');

/**
 * Auth routes
 */
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/**
 * Resources Routes
 */

 // Carrito Routes
Route::resource('carrito', 'CarritoController');
// Pedidos Routes
Route::resource('pedidos', 'PedidoController');


/**
 * JSON sources returns
 */
Route::get('json/pedidos/{pedido_id}', 'JsonDatasourceController@pedidosJson');
