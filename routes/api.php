<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ArticulosController;
use App\Http\Controllers\Api\OutputsController;
use App\Http\Controllers\Api\OutputsLinesController;
use App\Http\Controllers\Api\BodegaController;
use App\Http\Controllers\Api\LocalizacionController;
use App\Http\Controllers\Api\CostCenterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Api Resources Routes
 */
// Route::apiResource('articulos', ArticulosController::class);

// // Custom articulo search
Route::get('/articulos_custom_search', 
    [ArticulosController::class, 'customSearch'])
    ->name('articulos.custom.search');
// Custom articulo search by barcode
Route::get('/articulos_custom_barcodesearch', 
    [ArticulosController::class, 'customSearchWithBarcode'])
    ->name('articulos.custom.barcodesearch');
/**
 * 
 * Outputs Api Routes
 */
Route::get('/outputs/{package}', [OutputsController::class, 'index'])
    ->name('api.outputs.index');
Route::get('/outputs/{documento_inv}', [OutputsController::class, 'show'])
    ->name('api.outputs.show');
Route::post('/outputs', [OutputsController::class, 'store'])
    ->name('api.outputs.store');
// Select / Unselect documento_inv
Route::post('/outputs/{package}/toggle_selection',
    [OutputsController::class, 'toggleSelect'])
    ->name('outputs.toggleselection');
Route::post('outputsapprove', [OutputsController::class, 'switchApprove']);
Route::post('outputsapprove/{documento_inv}', [OutputsController::class,
    'specificApprove']);

/**
 * 
 * Outputs Lines Api Routes
 * 
 */
Route::get('/outputlines/{documento_inv}', [OutputsLinesController::class, 'index'])
    ->name('api.outputlines.index');
Route::post('/outputlines', [OutputsLinesController::class, 'store'])
    ->name('api.outputlines.store');
Route::delete('/outputlines', [OutputsLinesController::class, 'destroy'])
    ->name('api.outputlines.destroy');

/**
 * Warehouse api routes
 */
Route::get('/warehouses', [BodegaController::class, 'index']);
Route::get('/warehouses/{warehouse}', [BodegaController::class, 'getStorage']);

/**
 * Warehouse locations api routes
 */
Route::get('/locations', [LocalizacionController::class, 'index']);
Route::get('/locations/{warehouse}', [LocalizacionController::class,
    'getLocationFromStorage']);
Route::get('/locations/{warehouse}/{location}', [LocalizacionController::class,
    'getSpecificLocation']);

/**
 * Centro de Costo api Routes
 */
Route::get('/costcenters', [CostCenterController::class, 'index']);
