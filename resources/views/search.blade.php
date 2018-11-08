@extends('layouts.app')

@section('body-properties')
class="body-search"
@endsection

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="jumbotron custom-search text-center">
        <img src="{{ asset('/img/sc-min-icon.png')}}" alt="" class="justify-self-center">
        <h1 class="display-4 text-center">Busqueda de Repuestos</h1>
        <div class="">
          <form class="" action="{{ route('result') }}" method="get">
            @csrf
            <div class="input-group">
              <input type="text" name="criteria" required class="form-control border border-warning" placeholder="Buscar por nombre o número de parte" title="Puede buscar por nombre o número de parte" data-toggle="tooltip" data-placement="bottom">
              <div class="input-group-append">
                <button type="" class="btn btn-warning"><i class="fas fa-search"></i> Buscar</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
