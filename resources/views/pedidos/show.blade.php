@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <!-- Document Header -->
          <div class="row">
            <div class="col-sm-12">
              <h3>{{ 'SOL-'.str_pad($pedido->id, 8 , '0', STR_PAD_LEFT)}}</h3>
              <table class="table table-bordered border-primary">
                <tr>
                  <td class="text-center">
                    <img src="{{ asset('img/sc-min-icon.png') }}" style="width: 100px; height: 100px;" alt="logo">
                  </td>
                  <td class="text-center"><h3>Solicitud para salida de almacén</h3></td>
                  <td class="p-0 m-0">
                    <table class="table table-bordered">
                      <tr>
                        <td class="p-0 m-0">Fecha creación: <span class="float-right">23/01/2020</span></td>
                      </tr>
                      <tr>
                        <td class="p-0 m-0">Código: <span class="float-right">RE-MT-52</span></td>
                      </tr>
                      <tr>
                        <td class="p-0 m-0">Realizado Por: <span class="float-right">Juan Gabriel López</span></td>
                      </tr>
                      <tr>
                        <td class="p-0 m-0">Aprovado Por: <span class="float-right">Neyda Roca</span></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <!-- Seconds section header -->
          <div class="row">
            <div class="col-sm-6 float-right">
              <strong>N.I </strong>  {{ ($pedido->numero_interno !== null) ? $pedido->numero_interno : 'N/A' }} <br>
              <strong># Salida </strong>  {{ ($pedido->numero_salida !== null) ? $pedido->numero_salida : 'N/A' }} <br>
            </div>
            <div class="col-sm-6">
              <table class="table text-center">
                <tr>
                  <td class="border-top-0"><strong>DIA</strong></td>
                  <td class="border-top-0"><strong>MES</strong></td>
                  <td class="border-top-0"><strong>AñO</strong></td>
                </tr>
                <tr>
                  <td class="border">{{ ($pedido->fecha_entrega !== null ) ? $pedido->diaEntrega: '' }}</td>
                  <td class="border">{{ ($pedido->fecha_entrega !== null ) ? $pedido->mesEntrega: '' }}</td>
                  <td class="border">{{ ($pedido->fecha_entrega !== null ) ? $pedido->anoEntrega: '' }}</td>
                </tr>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <table class="table table-bordered">
                <thead>
                  <th>UND</th>
                  <th>REFERENCIA</th>
                  <th>Original</th>
                  <th>DESCRIPCIÓN DEL COMPONENTE</th>
                </thead>
                <tbody>
                  @foreach($pedido->detalle as $detalle)
                  <tr>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ $detalle->articulo->partNumber }}</td>
                    <td>
                      @if($detalle->articulo->CLASIFICACION_2 == '02-01')
                      <i class="fas fa-check"></i>
                      @endif
                    </td>
                    <td>{{ $detalle->articulo->partName }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <strong>Solicitado Por: </strong><span>{{ $pedido->usuario->name }}</span><br>
              <strong>Aprovado Por: </strong><span>{{ ($pedido->usuarioAutorizacion !== null) ? $pedido->usuarioAutorizacion->name: 'No Autorizado' }}</span><br>
              <strong>Entregado Por: </strong><span>{{ ($pedido->usuarioEntrega !== null) ? $pedido->usuarioEntrega->name : 'N/A' }}</span><br>
              <strong>Recivido Por: </strong><span>{{ ($pedido->usuarioRecepcion !== null) ? $pedido->usuarioRecepcion->name : 'N/A' }}</span><br>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<button type="btn btn-warning" class="btn btn-warning" style="@media: screen; position: fixed; right: 30px; bottom: 30px;" onclick="window.print()"><i class="fas fa-print fa-5x"></i></button>
@endsection
