@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card border border-dark">
        <div class="card-header bg-dark text-white">
          <h4>Historial de Movimientos, respuesto: <small>{{ $articulo->partName }} ({{ $articulo->partNumber }})</small> </h4>
        </div>
        <div class="card-body">
          <table class="table table-striled table-bordered table-hover">
            <thead>
              <tr>
                <th>Bodega</th>
                <th>Localización</th>
                <th>Tipo</th>
                <th>Usuario</th>
                <th>Fecha / Hora</th>
                <th>Documento</th>
                <th>Referencia</th>
                <th>Cantidad</th>
              </tr>
            </thead>
            <tbody>
              @foreach($transacciones as $trans)
              <tr>
                <td>{{ $trans->BODEGA }}</td>
                <td>{{ $trans->LOCALIZACION }}</td>
                <td>{{ $trans->auditTransInv->CONSECUTIVO }}</td>
                <td>{{ $trans->auditTransInv->USUARIO }}</td>
                <td>{{ $trans->FECHA_HORA_TRANSAC }}</td>
                <td>{{ $trans->auditTransInv->APLICACION }}</td>
                <td>{{ $trans->auditTransInv->REFERENCIA }}</td>
                <td>{{ $trans->CANTIDAD }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
