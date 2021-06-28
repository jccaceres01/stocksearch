@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <h3 class="text-dark d-inline">Carrito de Repuestos<sup><span class="badge badge-warning">{{ $items->count() }}</span></sup> </h3>
      <button type="button" class="btn btn-sm btn-outline-secondary d-inline">Hacer Pedido</button>
    </div>
    <div class="col-md-6">
      <div class="">
        <form class="" action="{{ route('result') }}" method="GET">
          @csrf
          <div class="input-group">
            <input type="text" name="criteria" required class="form-control border border-warning" placeholder="Seguir Buscando Repuestos">
            <div class="input-group-append">
              <button type="" class="btn btn-warning"><i class="fas fa-search"></i> Buscar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <hr>
      <!-- Catch request errors -->
      @if($errors->count() > 0)
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        @foreach($errors->all() as $er)
        <p>* {{ $er }}</p>
        @endforeach
      </div>
      @endif

      @foreach($items as $item)
      <div class="row">
        <div class="col-md-1">
          <span class="badge badge-warning" style="font-size:20px; font-weight:bold;">{{ $loop->iteration }}</span>
        </div>
        <div class="col-md-11">
          <div class="card ">
            <div class="card-body shadow-sm">
              <div class="row">
                <div class="col-sm-12">
                  <h3 class="d-inline"> {{ $item->articulo->partNumber }} </h3> <h4 class="d-inline text-muted">[ {{ $item->articulo->partName }} ]</h4>
                  <hr>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <strong>Bodega: </strong> <span class="text-muted">{{ $item->bodega->NOMBRE }} ({{ $item->bodega->BODEGA }})</span><br>
                  <strong>Localización: </strong> <span class="text-muted">{{ $item->localizacion }}</span><br>
                  <strong>Original: </strong>
                  @switch($item->original)
                    @case(true)
                      <i class="fas fa-check-circle text-success" title="Original" data-toggle="tooltip" data-placement="top"></i>
                      @break
                    @case(false)
                      <i class="fas fa-times-circle text-danger" title="AfterMaker" data-toggle="tooltip" data-placement="top"></i>
                      @break
                    @default
                      No Definido
                  @endswitch
                </div>
                <div class="col-sm-6">
                  <strong>Cantidad: </strong> <span>{{ $item->cantidad }}</span><br>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 text-right">
                  <form class="form d-inline" action="{{ route('carrito.destroy', $item->id) }}" method="post" onsubmit="return confirm('¿Desea quitar este repuesto del carrito?')">
                    @csrf
                    @method('delete')
                    <!-- Send form -->
                    <button type="submit" class="btn btn-sm btn-secondary">Remover</button>
                  </form>
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
</div>
@endsection
