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
  // if (auth()->check()) {
  //   return redirect()->route('search');
  // } else {
  //   return view('landing');
  // }
  return view('search');
});

/**
 * Auth routes
 */
Auth::routes();

/**
 * Routes for stock outputs
 * 
 */
Route::get('outputs', 'OutputsController@getOutputs')
  ->name('outputs.index'); // Get outputs
Route::get('outputs/{documento_inv}', 'OutputsController@getOutputsShow')
  ->name('outputs.show'); // Get outputs by documento_inv
Route::post('outputs', 'OutputsController@createOutputs')
  ->name('outputs.create');

/**
 * Search and Results Routes
 */
Route::get('search', 'SearchController@search')->name('search');
Route::get('result', 'SearchController@result')->name('result');
Route::post('rotation', 'SearchController@rotation')->name('article.rotation');

/**
 * Home
 */
Route::get('/home', 'HomeController@index')->name('home');

/**
 * Print Reports
 */
Route::post('/reports', 'ReportsController@printReport')->name('reports');
