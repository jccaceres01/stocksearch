<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OutputsRequests;

class OutputsController extends Controller
{
    
    // /**
    //  * Get Outputs (index - master)
    //  */

    public function index(Request $request, $package) {
        // Get approved outputs
        $approvedOutputs = \DB::connection('sqlsrv')->table('SOCOCO.DOCUMENTO_INV')
        ->where('CONSECUTIVO', 'SALIDA')
        ->where('PAQUETE_INVENTARIO', $package)
        ->whereNotNull('APROBADO')
        ->orderBy('FECHA_DOCUMENTO', 'ASC')
        ->get();

        // Get disapproved outputs
        $disapprovedOutputs = \DB::connection('sqlsrv')->table('SOCOCO.DOCUMENTO_INV')
        ->where('CONSECUTIVO', 'SALIDA')
        ->where('PAQUETE_INVENTARIO', $package)
        ->whereNull('APROBADO')
        ->orderBy('FECHA_DOCUMENTO', 'ASC')
        ->get();

        // Return Results
        return response()->json([
            'approvedOutputs' => $approvedOutputs,
            'disapprovedOutputs' => $disapprovedOutputs,
        ]);
    }

    /**
     * Get outputs by specific documento_inv (Show - detail) 
     */
    public function show($documento_inv) {
        $output = \DB::connection('sqlsrv')->table('SOCOCO.DOCUMENTO_INV')
        ->where('DOCUMENTO_INV', $documento_inv)->first();

        return response()->json($output, 200);
    }

    /**
     * Create new Output
     */
    public function store(OutputsRequests $request) {
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
            'USUARIO' => $request->softland_user['USUARIO'],
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
            return response()->json($output, 200);
        } catch (\Exception $e) {
            \DB::rollback();
            info($e);
            abort(500);
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Select or unselect output
     */
    public function toggleSelect(Request $request) {

        $currentOption = \DB::connection('sqlsrv')->table('SOCOCO.DOCUMENTO_INV')
            ->where('DOCUMENTO_INV', $request->documento_inv)
            ->pluck('SELECCIONADO')
            ->first();

        $switchedSelected = ($currentOption === 'S') ? 'N' : 'S';

        try {
            \DB::connection('sqlsrv')->table('SOCOCO.DOCUMENTO_INV')
                ->where('DOCUMENTO_INV', $request->documento_inv)
                ->update([
                    'SELECCIONADO' => $switchedSelected
                ]);

            return response()->json($switchedSelected, 200);
        } catch (\Exception $ex) {
            info($ex);
            return response()->json($ex->getMessage(), 500);
        }
    }

    /**
     * Approve / Disapprove outputs documents
     */
    public function switchApprove(Request $request) {
        $now = new \DateTime('now');

        try {
            if ($request->action ===  'approve') {
                \DB::connection('sqlsrv')->table('SOCOCO.DOCUMENTO_INV')
                    ->where('PAQUETE_INVENTARIO', $request->paquete_inv)
                    ->where('SELECCIONADO', 'S')
                    ->whereNull('APROBADO')
                ->update([
                    'USUARIO_APRO' => $request->softland_user,
                    'FECHA_HORA_APROB' => $now,
                    'APROBADO' => 'S',
                    'SELECCIONADO' => 'N'
                ]);
    
                return response()->json('Documentos Aprobados', 200);
            } elseif ($request->action === 'disapprove') {
                \DB::connection('sqlsrv')->table('SOCOCO.DOCUMENTO_INV')
                    ->where('PAQUETE_INVENTARIO', $request->paquete_inv)
                    ->where('SELECCIONADO', 'S')
                    ->whereNotNull('APROBADO')
                    ->whereNotNull('FECHA_HORA_APROB')
                ->update([
                    'USUARIO_APRO' => null,
                    'FECHA_HORA_APROB' => null,
                    'APROBADO' => null,
                    'SELECCIONADO' => 'N'
                ]);
    
                return response()->json('Documentos Desaprobados', 200);
            }
    
            return response()->json('No hay acción', 200);
        } catch (\Exception $ex) {
            switch ($ex->getCode()) {
                default:
                    info($ex);
                    return response()->json($ex->getMessage(), 500);
            }
        }
    }


    /**
     * Approve / Disapprove outputs documents by documento_inv reference
     */
    public function specificApprove(Request $request, $documento_inv) {
        $now = new \DateTime('now');
        info($request->all());

        try {
            if ($request->action ===  'approve') {
                \DB::connection('sqlsrv')->table('SOCOCO.DOCUMENTO_INV')
                    ->where('DOCUMENTO_INV', $documento_inv)
                    ->whereNull('APROBADO')
                ->update([
                    'USUARIO_APRO' => $request->softland_user,
                    'FECHA_HORA_APROB' => $now,
                    'APROBADO' => 'S',
                    'SELECCIONADO' => 'N'
                ]);
    
                $documento = \DB::connection('sqlsrv')->table('SOCOCO.DOCUMENTO_INV')
                    ->where('DOCUMENTO_INV', $documento_inv)->first();

                return response()->json('Documento Aprobado', 200);
            } elseif ($request->action === 'disapprove') {
                \DB::connection('sqlsrv')->table('SOCOCO.DOCUMENTO_INV')
                    ->where('DOCUMENTO_INV', $documento_inv)
                ->update([
                    'USUARIO_APRO' => null,
                    'FECHA_HORA_APROB' => null,
                    'APROBADO' => null,
                    'SELECCIONADO' => 'N'
                ]);

                $documento = \DB::connection('sqlsrv')->table('SOCOCO.DOCUMENTO_INV')
                    ->where('DOCUMENTO_INV', $documento_inv)->first();
    
                return response()->json('Documento Desaprobado', 200);
            }
    
            return response()->json($documento, 200);
        } catch (\Exception $ex) {
            switch ($ex->getCode()) {
                default:
                    info($ex);
                    return response()->json($ex->getMessage(), 500);
            }
        }
    }
}