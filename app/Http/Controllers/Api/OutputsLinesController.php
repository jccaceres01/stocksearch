<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OutputsLinesRequest;

class OutputsLinesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($documento_inv) {
        $lines = \DB::connection('sqlsrv')->table('SOCOCO.LINEA_DOC_INV')
        ->join('SOCOCO.ARTICULO','ARTICULO.ARTICULO',
            '=','LINEA_DOC_INV.ARTICULO')
        ->where('PAQUETE_INVENTARIO', 'SAL')
        ->where('DOCUMENTO_INV', $documento_inv)->get();

        return response()->json($lines, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * 
     * Create new output line
     */
    public function store(OutputsLinesRequest $request) {
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
            return response()->json('No existe Lote', 404);
        }

        //  Find existencia bodega
        $existenciaBodega = \DB::connection('sqlsrv')->table('SOCOCO.EXISTENCIA_BODEGA')
            ->where('ARTICULO', $request->articulo)
            ->where('BODEGA', $request->bodega)->first();
        if ($existenciaBodega == null) {
            return response()->json('No hay existencia en bodega', 404);
        }

        if ($request->cantidad <= $existenciaLote->CANT_DISPONIBLE) {
            \DB::connection('sqlsrv')->beginTransaction();
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
            \DB::connection('sqlsrv')->table('SOCOCO.EXISTENCIA_BODEGA')
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
                        'USUARIO' => $request->softland_user,
                        'FECHA_HORA' => new \DateTime('now')
                    ]);
            }
            //  Insert new lines for documento_inv
            \DB::connection('sqlsrv')->table('SOCOCO.LINEA_DOC_INV')
                ->insert($data);

            $outputLine = \DB::connection('sqlsrv')->commit();
            return response()->json($outputLine, 200);
            } catch (\Exception $e) {
                switch ($e->getCode()) {
                    default:
                        \DB::connection('sqlsrv')->rollback();
                        info($e);
                        return response()->json($e->getMessage(), 500);
                }
            }
        } else {
            // the quantity is more than the existence
            return response()->json('La cantidad sobrepasa la existencia en '
            .'[Localización, Bodega]', 409);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {

        $existenciaLote = null;
        $existenciaBodega = null;
        $existenciaReserva = null;

        // Find existencia lote
        if (($existenciaLote = \DB::connection('sqlsrv')
            ->table('SOCOCO.EXISTENCIA_LOTE')
            ->where('ARTICULO', $request->articulo)
            ->where('BODEGA', $request->bodega)
            ->where('LOCALIZACION', $request->localizacion)
            ->where('LOTE', 'ND')->first()) != true) {
            return response()->json('No hay existencia en lote', 404);
        }

        //  Find existencia bodega
        if (($existenciaBodega = \DB::connection('sqlsrv')
            ->table('SOCOCO.EXISTENCIA_BODEGA')
            ->where('ARTICULO', $request->articulo)
            ->where('BODEGA', $request->bodega)->first()) != true) {
            return response()->json('No hay existencia en bodega', 404);
        }

        // Find existencia reserva
        if (($existenciaReserva = \DB::connection('sqlsrv')
            ->table('SOCOCO.EXISTENCIA_RESERVA')
            ->where('ARTICULO', $request->articulo)
            ->where('APLICACION', $request->documento_inv)
            ->where('BODEGA', $request->bodega)
            ->where('LOTE', 'ND')
            ->where('LOCALIZACION', $request->localizacion)->first()) != true) {
                return response()->json('No hay existencia en reserva', 404);
        }
        // Start Transaccional engine for connection sqlsrv
        \DB::connection('sqlsrv')->beginTransaction();
        try {
            // Update existencia lote
            \DB::connection('sqlsrv')
            ->table('SOCOCO.EXISTENCIA_LOTE')
            ->where('ARTICULO', $request->articulo)
            ->where('BODEGA', $request->bodega)
            ->where('LOCALIZACION', $request->localizacion)
            ->where('LOTE', 'ND')
            ->update([
                'CANT_DISPONIBLE' =>
                $existenciaLote->CANT_DISPONIBLE + $request->cantidad,
                'CANT_RESERVADA' =>
                $existenciaLote->CANT_RESERVADA - $request->cantidad
            ]);

            // Update existencia bodega
            \DB::connection('sqlsrv')
            ->table('SOCOCO.EXISTENCIA_BODEGA')
            ->where('ARTICULO', $request->articulo)
            ->where('BODEGA', $request->bodega)
            ->update([
                'CANT_DISPONIBLE' =>
                $existenciaLote->CANT_DISPONIBLE + $request->cantidad,
                'CANT_RESERVADA' =>
                $existenciaLote->CANT_RESERVADA - $request->cantidad
            ]);

            if (($existenciaReserva->CANTIDAD - $request->cantidad) == 0) {
            \DB::connection('sqlsrv')->table('SOCOCO.EXISTENCIA_RESERVA')
                ->where('ARTICULO', $request->articulo)
                ->where('APLICACION', $request->documento_inv)
                ->where('BODEGA', $request->bodega)
                ->where('LOTE', 'ND')
                ->where('LOCALIZACION', $request->localizacion)->delete();
            } else {
            \DB::connection('sqlsrv')->table('SOCOCO.EXISTENCIA_RESERVA')
                ->where('ARTICULO', $request->articulo)
                ->where('APLICACION', $request->documento_inv)
                ->where('BODEGA', $request->bodega)
                ->where('LOTE', 'ND')
                ->where('LOCALIZACION', $request->localizacion)
                ->update([
                    'CANTIDAD' => $existenciaReserva->CANTIDAD
                        - $request->cantidad
                ]);
            }

            // Delete Line
            \DB::connection('sqlsrv')
            ->table('SOCOCO.LINEA_DOC_INV')
            ->where('PAQUETE_INVENTARIO', $request->paquete_inventario)
            ->where('DOCUMENTO_INV', $request->documento_inv)
            ->where('LINEA_DOC_INV', $request->linea)
            ->delete();

            // Commit transaction for sqlsrv connection
            \DB::connection('sqlsrv')->commit();
            return response()->json('Linea Borrada', 200);

        } catch (\Exception $e) {
            switch ($e->getCode()) {
            default:
                // Roll back transaction to connection sqlsrv
                \DB::connection('sqlsrv')->rollback();
                info($e);
                abort(500);
                return response()->json($e->getMessage(), 500);
            }
        }
    }
}
