@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <form class="" action="{{ route('pedidos.index') }}" method="get">
        <div class="input-group">
          <input type="text" class="form-control" name="criteria" placeholder="Buscar Pedido">
          <div class="input-group-append">
            <button type="submit" class="btn btn-secondary input-group-btn"><i class="fas fa-search"></i></button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <br>
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Pedidos <span class="text-muted"></span></h3>
        </div>
        <div class="card-body m-0 p-0">
          <table class="table table-striped table-bordered">
            <thead class="bg-dark text-light">
              <tr>
                <td>No.#</td>
                <td>N.I</td>
                <td>Controles</td>
              </tr>
            </thead>
            <tbody>
              @foreach($pedidos as $pedido)
              <tr>
                <td>{{ 'SOL-'.str_pad($pedido->id, 8 , '0', STR_PAD_LEFT) }}</td>
                <td>{{ $pedido->numero_interno }}</td>
                <td>
                  <a href="{{ route('pedidos.show', $pedido->id) }}" class="bnt btn-secondary btn-sm"><i class="fas fa-eye"></i></a>
                  <a href="#" class="bnt btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                  <a href="#" class="bnt btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <br>
      <div class="text-center">
        {{ $pedidos->render() }}
      </div>
    </div>
  </div>
</div>
@endsection
