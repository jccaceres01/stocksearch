@extends('layouts.app')

@section('content-header')
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <h3>Salida De Repuestos</h3>
      <hr>
    </div>
  </div>
  @if($errors->count() > 0)
  <div class="row">
    <div class="col-sm-12">
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        @foreach($errors->all() as $er)
        <p>* {{ $er }} </p>
        @endforeach
        </div>
      </div>
    </div>
  </div>
  @endif
@endsection

@section('content')
  <output-index-component :softland_user="{{ auth()->user()->softlandUser }}"> </output-index-component>
@endsection