<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Rquests\OutputsRequest;
use App\Http\Reuqets\OutputsLinesRequests;
use App\Models\DocumentoInv;

use App\http\Requests\OutputsRequests;

class OutputsController extends Controller
{
    /**
   * Get Outputs (index - master)
   */
    public function getOutputs(Request $request) {
        // Get approved outputs

        // Return Results
        return view('outputs.index');
    }

    /**
     * Get outputs by specific documento_inv (Show - detail) 
     */
    public function getOutputsShow($documento_inv) {
        $output = \DB::connection('sqlsrv')->table('SOCOCO.DOCUMENTO_INV')
        ->where('DOCUMENTO_INV', $documento_inv)->first();

        return view('outputs.show')->with([
            'output' => json_encode($output)
        ]);
    }

    /**
     * Create new Output
     */
    public function createOutputs(OutputsRequests $request) {
        $maskedCons = \DB::connection('sqlsrv')
            ->table('SOCOCO.CONSECUTIVO_CI')
            ->where('CONSECUTIVO', 'SALIDA')
            ->pluck('SIGUIENTE_CONSEC')->first();

        $cons = substr($maskedCons, strpos($maskedCons, '-') + 1,
            strlen($maskedCons));

        $nextCons = 'SAL-'.str_pad($cons+1, 6, '0', STR_PAD_LEFT);

        $data = [
            'REFERENCIA' => strtoupper($request->referencia),
            'PAQUETE_INVENTARIO' => strtoupper($request->paquete_inventario),
            'DOCUMENTO_INV' => 'SAL-'.$cons,
            'FECHA_HOR_CREACION' => new \DateTime('now'),
            'FECHA_DOCUMENTO' => new \DateTime('now'),
            'SELECCIONADO' => 'N',
            'USUARIO' => auth()->user()->softlandUser->USUARIO,
            'CONSECUTIVO' => 'SALIDA'
        ];

        \DB::beginTransaction();
        try {
            \DB::connection('sqlsrv')
                ->table('SOCOCO.CONSECUTIVO_CI')->where('CONSECUTIVO', 'SALIDA')
                ->update([
                    'ULTIMO_USUARIO' => 'SA',
                    'SIGUIENTE_CONSEC' => $nextCons
                ]);
            // Create Output
            \DB::connection('sqlsrv')
                ->table('SOCOCO.DOCUMENTO_INV')->insert($data);

            // Get the new output
            $output = \DB::connection('sqlsrv')
                ->table('SOCOCO.DOCUMENTO_INV')
                ->where('DOCUMENTO_INV', 'SAL-'.$cons)
                ->first();

            \DB::commit();
            toastr()->success('Listo para agregar lineas', 'Salida Creada');
            return redirect()->route('outputs.show', $output->DOCUMENTO_INV);
        } catch (\Exception $e) {
            \DB::rollback();
            info($e);
            toastr()->error($e->getMessage(), $e->getCode());
            return back();
        }
    }

    /**
     * Output Line form
     */
    public function outputLineForm(Request $request) {
        return view('outputs.lineform');
    }

    /**
     * Create new output line
     */
    public function createLine(Request $request) {
        $lineNumber = \DB::connection('sqlsrv')->table('SOCOCO.LINEA_DOC_INV')
            ->where('PAQUETE_INVENTARIO', $request->paquete_inventario)
            ->where('DOCUMENTO_INV', $request->documento_inv)
            ->max('LINEA_DOC_INV') + 1;

        $data = [
            'PAQUETE_INVENTARIO' => $request->paquete_inventario,
            'DOCUMENTO_INV' => $request->documento_inv,
            'ARTICULO' => $request->articulo,
            'BODEGA' => $request->bodega,
            'LOCALIZACION' => $request->localizacion,
            'CANTIDAD' => $request->cantidad,
            'LINEA_DOC_INV' => $lineNumber,
            'AJUSTE_CONFIG' => '~CC~',
            'NIT' => '130412618',
            'TIPO' => 'C',
            'SUBTIPO' => 'R',
            'SUBSUBTIPO' => 'N',
            'COSTO_TOTAL_LOCAL' => 0,
            'COSTO_TOTAL_DOLAR' => 0,
            'PRECIO_TOTAL_LOCAL' => 0,
            'PRECIO_TOTAL_DOLAR' => 0,
            'BODEGA_DESTINO' => null,
            'LOCALIZACION_DEST' => null,
            'CENTRO_COSTO' => $request->centro_costo,
            'SECUENCIA' => null,
            'SERIE_CADENA' => null,
            'UNIDAD_DISTRIBUCIO' => null,
            'CUENTA_CONTABLE' => '5-01-01-001-000',
            'COSTO_TOTAL_LOCAL_COMP' => 0,
            'COSTO_TOTAL_DOLAR_COMP' => 0
        ];

        $existenciaLote = null;
        $existenciaBodega = null;
        // $existenciaReserva = null;

        // Find existencia lote
        $existenciaLote = \DB::connection('sqlsrv')->table('SOCOCO.EXISTENCIA_LOTE')
            ->where('ARTICULO', $request->articulo)
            ->where('BODEGA', $request->bodega)
            ->where('LOCALIZACION', $request->localizacion)
            ->where('LOTE', 'ND')->first();
        if ($existenciaLote == null) {
            toastr()->warning('No existe Lote', 'Advertencia');
            return back()->withInputs();
        }

        //  Find existencia bodega
        $existenciaBodega = \DB::connection('sqlsrv')->table('SOCOCO.EXISTENCIA_BODEGA')
            ->where('ARTICULO', $request->articulo)
            ->where('BODEGA', $request->bodega)->first();
        if ($existenciaBodega == null) {
            toastr()->warning('No hay existencia en bodega', 'Advertencia');
            return back()->withInputs();
        } 

        if ($request->cantidad <= $existenciaLote->CANT_DISPONIBLE) {
            \DB::beginTransaction();
            try {
            // Update ExistenciaLote
            \DB::connection('sqlsrv')->table('SOCOCO.EXISTENCIA_LOTE')
                ->where('ARTICULO',  $request->articulo)
                ->where('LOTE', 'ND')->where('LOCALIZACION', $request->localizacion)
                ->where('BODEGA', $request->bodega)
                ->update([
                'CANT_DISPONIBLE' =>
                    $existenciaLote->CANT_DISPONIBLE - $request->cantidad,
                'CANT_RESERVADA' =>
                    $existenciaLote->CANT_RESERVADA + $request->cantidad
                ]);

            // Update Existencia Bodega
            \DB::connectoin('sqlsrv')->table('SOCOCO.EXISTENCIA_BODEGA')
                ->where('BODEGA', $request->bodega)
                ->where('ARTICULO', $request->articulo)
                ->update([
                'CANT_DISPONIBLE' =>
                    $existenciaBodega->CANT_DISPONIBLE - $request->cantidad,
                'CANT_RESERVADA' =>
                    $existenciaLote->CANT_RESERVADA + $request->cantidad
                ]);

            // Create or update existencia reserva
            if (( $existenciaReserva = \DB::connection('sqlsrv')
                ->table('SOCOCO.EXISTENCIA_RESERVA')
                ->where('ARTICULO', $request->articulo)
                ->where('APLICACION', $request->documento_inv)
                ->where('BODEGA', $request->bodega)
                ->where('LOTE', 'ND')
                ->where('LOCALIZACION', $request->localizacion)
                ->first()) == true) {
                \DB::connection('sqlsrv')->table('SOCOCO.EXISTENCIA_RESERVA')
                    ->where('ARTICULO', $request->articulo)
                    ->where('APLICACION', $request->documento_inv)
                    ->where('BODEGA', $request->bodega)
                    ->where('LOTE', 'ND')
                    ->where('LOCALIZACION', $request->localizacion)
                    ->update([
                    'CANTIDAD' => $existenciaReserva->CANTIDAD 
                        + $request->cantidad
                    ]);
            } else {
                \DB::connection('sqlsrv')->table('SOCOCO.EXISTENCIA_RESERVA')
                    ->insert([
                        'ARTICULO' => $request->articulo,
                        'APLICACION' => $request->documento_inv,
                        'BODEGA' => $request->bodega,
                        'LOTE' => 'ND',
                        'LOCALIZACION' => $request->localizacion,
                        'MODULO_ORIGEN' => 'CI',
                        'CANTIDAD' => $request->cantidad,
                        'USUARIO' => auth()->user()->softlandUser->USUARIO,
                        'FECHA_HORA' => new \DateTime('now')
                    ]);
            }
            //  Insert new lines for documento_inv
            \DB::connection('sqlsrv')->table('SOCOCO.LINEA_DOC_INV')
                ->insert($data);

            \DB::commit();
            toastr()->success('Linea Insertada', 'Información');
            return back();
            } catch (\Exception $e) {
                switch ($e->getCode()) {
                    default:
                        \DB::rollback();
                        info($e);
                        toastr()->error($e->getMessage(), 
                            'Error '.$e->getCode().': ');
                        return back()->withInputs();
                }
            }
        } else {
            // the quantity is more than the existence
            toastr()->warning('La cantidad sobrepasa la existencia en '
                .'[Localización, Bodega]', 'Advertencia');
            return back()->withInputs();
        }
    }
}
