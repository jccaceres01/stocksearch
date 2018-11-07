@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <h3> Resultados de busqueda: <span class="badge badge-secondary">{{ $articulos->total() }}</span></h3>
    </div>
    <div class="col-md-6">
      <div class="">
        <form class="" action="{{ route('result') }}" method="GET">
          @csrf
          <div class="input-group">
            <input type="text" name="criteria" required class="form-control" placeholder="Buscar por nombre o número de parte">
            <div class="input-group-append">
              <button type="" class="btn btn-secondary"><i class="fas fa-search"></i> Buscar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <hr>
      @if($articulos->total() > 100 )
      <h5 class="text-center text-danger">Se obtuvieron más de 100 resultados. Para reducir la cantidad mostrada, use un número de parte en vez de una descripción.</h5>
      @endif
      @foreach($articulos as $art)
      <div class="row">
        <div class="col-md-1">
          <span class="badge badge-secondary" style="font-size:20px; font-weight:bold;">{{ ($articulos->currentpage()-1) * $articulos->perpage() + $loop->index + 1 }}</span>
        </div>
        <div class="col-md-11">
          <div class="card border border-dark">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6">
                  <table>
                    <tr>
                      <td><strong>Código Softland: </strong> {{ $art->ARTICULO }}</td>
                    </tr>
                    <tr>
                      <td><strong>Número de Parte: </strong> {{ $art->partNumber }}</td>
                    </tr>
                    <tr>
                      <td><strong>Nombre: </strong> {{ $art->partName }}</td>
                    </tr>
                  </table>
                </div>
                <div class="col-md-6">
                  <table>
                    <tr>
                      <td><strong>Disponible:</strong> <span class="badge badge-success"> {{ $art->existenciaBodega->sum('CANT_DISPONIBLE') }}</span></td>
                    </tr>
                    <tr>
                      <td><strong>Reservada:</strong> <span class="badge badge-warning"> {{ $art->existenciaBodega->sum('CANT_RESERVADA') }}</span></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h5>Localizaciones:</h5>
                  <hr>
                  <table class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <td><strong>Bodega</strong></td>
                        <td><strong>Localización</strong></td>
                        <td><strong>Disponible</strong></td>
                        <td><strong>Reservada</strong></td>
                      </tr>
                    </thead>
                    <tbody>
                      @if(!empty($art->existenciaLote))
                      @foreach($art->existenciaLote as $el)
                      <tr>
                        <td>{{ $el->BODEGA }} ({{ $el->storage->NOMBRE }})</td>
                        <td>{{ $el->LOCALIZACION }}</td>
                        <td>{{ $el->CANT_DISPONIBLE }}</td>
                        <td>{{ $el->CANT_RESERVADA }}</td>
                      </tr>
                      @endforeach
                      @endif
                    </tbody>
                  </table>
                </div>
                <div class="col-md-6">
                  <h5>Alternos / Cambios:</h5>
                  <hr>
                  <table class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th><strong>Cod. Softland</strong></th>
                        <th><strong>No. Parte</strong></th>
                        <th><strong>Nombre</strong></th>
                        <th><strong><i class="fas fa-eye"></i></strong></th>
                      </tr>
                    </thead>
                    <tbody>
                      @if (!empty($art->alternos))
                      @foreach($art->alternos as $alt)
                      <tr>
                        <td>{{ $alt->ARTICULO }}</td>
                        <td>{{ $alt->partNumber }}</td>
                        <td>{{ $alt->partName }}</td>
                        <td><a href="#">Consultar</a></td>
                      </tr>
                      @endforeach
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <br>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <hr>
      <div class="justify-content-center">
        {{ $articulos->appends(\Request::except('page'))->render() }}
      </div>
    </div>
  </div>
</div>
@endsection
