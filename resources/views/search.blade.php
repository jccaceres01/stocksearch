@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="jumbotron">
        <h1 class="display-4 text-center">Busqueda de Repuestos</h1>
        <div class="">
          <form class="" action="{{ route('result') }}" method="get">
            @csrf
            <div class="input-group">
              <input type="text" name="criteria" required class="form-control" placeholder="Buscar por nombre o número de parte" title="Puede buscar por nombre o número de parte" data-toggle="tooltip" data-placement="bottom">
              <div class="input-group-append">
                <button type="" class="btn btn-secondary"><i class="fas fa-search"></i> Buscar</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
