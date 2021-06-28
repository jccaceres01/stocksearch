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
            <a href="{{ url('/login')}}" class="btn btn-warning text-center" id="landing-button">Iniciar Sesión<i class="fas fa-sign-in"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
