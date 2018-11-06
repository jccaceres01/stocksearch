@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <h3> Resultado de busqueda: <span class="badge badge-secondary">{{ $articulos->total() }}</span></h3>
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
      <span>{{ $art->DESCRIPCION }}</span><br>
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
