@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <h3 class="text-dark"> Resultados de busqueda: <span class="badge badge-warning">{{ $articulos->total() }}</span></h3>
    </div>
    <div class="col-md-6">
      <div class="">
        <form class="" action="{{ route('result') }}" method="GET">
          @csrf
          <div class="input-group">
            <input type="text" name="criteria" required class="form-control border border-warning" placeholder="Buscar por nombre o número de parte">
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
      @if ($errors->count() > 0)
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        @foreach($errors->all() as $er)
        <p>* {{ $er }}</p>
        @endforeach
      </div>
      @endif
      @if($articulos->total() > 100 )
      <h5 class="text-center text-danger">Se obtuvieron más de 100 resultados. Para reducir la cantidad mostrada, use un número de parte en vez de una descripción.</h5>
      @endif
      @foreach($articulos as $art)
      <div class="row">
        <div class="col-md-1">
          <span class="badge badge-warning" style="font-size:20px; font-weight:bold;">{{ ($articulos->currentpage()-1) * $articulos->perpage() + $loop->index + 1 }}</span>
        </div>
        <div class="col-md-11">
          <div class="card border border-dark">
            <div class="card-header bg-dark text-white">
              <div class="row">
                <div class="col-md-5">
                  <table>
                    <tr>
                      <td><strong class="text-warning">Código Softland: </strong> {{ $art->ARTICULO }}</td>
                    </tr>
                    <tr>
                      <td><strong class="text-warning">Número de Parte: </strong> {{ $art->partNumber }}</td>
                    </tr>
                    <tr>
                      <td><strong class="text-warning">Nombre: </strong> {{ $art->partName }}</td>
                    </tr>
                  </table>
                </div>
                <div class="col-md-5">
                  <table>
                    <tr>
                      <td><strong class="text-warning">Disponible:</strong> <span class="badge badge-secondary"> {{ $art->existenciaBodega->sum('CANT_DISPONIBLE') }}</span></td>
                    </tr>
                    <tr>
                      <td><strong class="text-warning">Reservada:</strong> <span class="badge badge-secondary"> {{ $art->existenciaBodega->sum('CANT_RESERVADA') }}</span></td>
                    </tr>
                    <tr>
                      <td>
                        <strong class="text-warning">Original: </strong>
                        @if(!empty($art->original))
                          @switch($art->CLASIFICACION_2)
                            @case('02-01')
                              <i class="fas fa-check-circle text-success" title="Original" data-toggle="tooltip" data-placement="top"></i>
                              @break
                            @case('02-02')
                              <i class="fas fa-times-circle text-danger" title="AfterMaker" data-toggle="tooltip" data-placement="top"></i>
                              @break
                            @default
                              No Definido
                          @endswitch
                        @endif
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="col-md-2">
                  <!-- Button trigger modal -->
                  <button type="button" class="btn btn-warning" onclick="setForm({{ json_encode($art->ARTICULO) }})">
                    Historial <i class="fas fa-angle-right"></i>
                  </button>
                </div>
              </div>
            </div>
            <div class="card-body p-0 m-0">
              <ul class="nav nav-tabs" id="tabbed1" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#tab1-{{ ($articulos->currentpage()-1) * $articulos->perpage() + $loop->index + 1 }}" role="tab" aria-controls="tab1" aria-selected="true"><strong>Localizaciones</strong></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab2-{{ ($articulos->currentpage()-1) * $articulos->perpage() + $loop->index + 1 }}" role="tab" aria-controls="tab2" aria-selected="false"><strong>Alternos / Cambios</strong></a>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab1-{{ ($articulos->currentpage()-1) * $articulos->perpage() + $loop->index + 1 }}" role="tabpanel" aria-labelledby="home-tab">
                  <!-- Loalizaciones content -->
                  <div class="row">
                    <div class="col-sm-12">

                       <table class="table table-striped table-bordered table-hover">
                         <thead>
                           <tr>
                             <td><strong>Bodega</strong></td>
                             <td><strong>Localización</strong></td>
                             <td><strong>Disponible</strong></td>
                             <td><strong>Reservada</strong></td>
                             <td><strong><i class="fas fa-cart"></i> Añadir al Carrito</strong></td>
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
                             <td>
                               <form class="form d-inline" action="{{ route('carrito.store') }}" method="post">
                                 <div class="row">
                                   <div class="col-sm-6">
                                     @csrf

                                     <!-- Hidden info for each form -->
                                     <input type="hidden" name="articulo_id" value="{{ $art->ARTICULO }}">
                                     <input type="hidden" name="bodega_id" value="{{ $el->BODEGA }}">
                                     <input type="hidden" name="localizacion" value="{{ $el->LOCALIZACION }}">
                                     <input type="hidden" name="original" value="{{ ($art->CLASIFICACION_2 == '02-01') ? 1 : 0 }}">

                                    <input type="number" steps="0.1" max="{{ $el->CANT_DISPONIBLE }}" min="1" class="d-inline form-control form-control-sm" name="cantidad" required>
                                   </div>
                                   <div class="col-sm-6">
                                     <div class="btn-group" role="group">
                                       <button type="reset" class="btn btn-secondary btn-sm" title="Limpiar"><i class="fas fa-retweet"></i> </button>
                                       <button type="submit" class=" d-inline btn btn-secondary btn-sm" title="Añadir al carrito"><i class="fas fa-shopping-cart"></i> </button>
                                     </div>
                                   </div>
                                 </div>
                               </form>
                             </td>
                           </tr>
                           @endforeach
                           @endif
                         </tbody>
                       </table>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="tab2-{{ ($articulos->currentpage()-1) * $articulos->perpage() + $loop->index + 1 }}" role="tabpanel" aria-labelledby="profile-tab">
                  <!-- Alternos content -->
                  <div class="row">
                    <div class="col-sm-12">
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
                            <td>
                              <form class="" action="{{ route('result') }}" method="GET">
                                @csrf
                                <input type="hidden" name="criteria" value="{{ $alt->partNumber }}">
                                <button type="submit" class="btn btn-secondary btn-sm">{{ $alt->existenciaBodega->sum('CANT_DISPONIBLE') }} Consultar</button>
                              </form>
                            </td>
                          </tr>
                          @endforeach
                          @endif
                        </tbody>
                      </table>
                    </div>
                  </div>
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

@section('post-body')
<!-- Modal for historical items -->
<div class="modal fade" id="article_historical" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark text-warning">
        <h5 class="modal-title" id="#">Consultar Historial de Repuesto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form" action="{{ route('article.rotation') }}" method="post" target="_blank">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <!-- start_date -->
              <div class="form-group">
                <label for="start_date">Fecha Inicial</label>
                <input type="date" class="form-control" name="start_date" placeholder="Fecha Inicial" required>
              </div>

              <!-- end_date -->
              <div class="form-group">
                <label for="end_date">Fecha Final</label>
                <input type="date" class="form-control" name="end_date" placeholder="Fecha Final" required>
              </div>

              <!-- code -->
              <div class="form-group">
                <label for="code">Código</label>
                <input type="text" class="form-control" name="code" placeholder="Código" required>
              </div>

            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-warning">Ver <i class="fas fa-search"></i></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal for historical items -->
@endsection

@section('post-script')
<script type="text/javascript">
  function setForm(articulo) {
    var startDate = document.querySelector('input[name="start_date"]')
    var endDate = document.querySelector('input[name="end_date"]')
    var code = document.querySelector('input[name="code"]')

    startDate.value = Date.now()
    endDate.value = Date.now()
    code.value = articulo

    $('#article_historical').modal('show')
  }
</script>
@endsection
