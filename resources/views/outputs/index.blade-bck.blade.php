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
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
      <div class="card">
        <div class="card-header bg-dark text-white">
          <div class="row">
            <div class="col-sm-6">
            <h5 class="d-inline">Paquete de Salidas: ( {{ $package }} )</h5>
            </div>
            <div class="col-sm-6">
              <button type="button" class="btn btn-secondary btn-sm float-right" title="Nueva Salida" data-toggle="tooltip" data-placement="top" id="showModal"><i class="fas fa-plus-circle"></i></button>
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <ul class="nav nav-tabs" id="navtab-id" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#tab1-name" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-check-circle text-success"></i> Aprobados <sup><span class="badge badge-secondary">{{ $approvedOutputs->count() }}</span></sup></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab2-name" role="tab" aria-controls="profile" aria-selected="false"><i class="fas fa-check-circle text-danger"></i> No Aprobados <sup><span class="badge badge-secondary">{{ $disapprovedOutputs->count() }}</span></sup></a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab1-name" role="tabpanel" aria-labelledby="home-tab">
              @if ($approvedOutputs->count() > 0)
              <!-- Add Content bellow -->
              <table class="table table-sm table-striped table-bordered m-0">
                <thead>
                  <tr>
                    <th>Estatus</th>
                    <th>Fecha Doc.</th>
                    <th>Documento</th>
                    <th>Usario</th>
                    <th>Referencia</th>
                    <th>Fecha de Aprovaci&oacute;n</th>
                    <th>Usuario Provaci&oacute;n</th>
                    <th><i class="fas fa-cog"></i></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($approvedOutputs as $approved)
                  <tr>
                    <td>
                    @switch($approved->APROBADO)
                      @case(null)
                        <i class="fas fa-check-circle text-danger"></i> No Aprobado
                        @break
                      @case('S')
                      <i class="fas fa-check-circle text-success"></i> Aprobado
                        @break
                      @default
                        'No Define'
                        @break
                    @endswitch  
                    </td>
                    <td>{{ date('d-m-Y', strtotime($approved->FECHA_DOCUMENTO)) }}</td>
                    <td>{{ $approved->DOCUMENTO_INV }}</td>
                    <td>{{ $approved->USUARIO }}</td>
                    <td>{{ $approved->REFERENCIA }}</td>
                    <td>
                    @if($approved->USUARIO_APRO != null)
                    {{ date('d-m-Y H:i:s', strtotime($approved->FECHA_HORA_APROB)) }}
                    @else
                    -
                    @endif
                    </td>
                    <td>
                    @if($approved->USUARIO_APRO != null)
                    {{ $approved->USUARIO_APRO }}
                    @else
                    -
                    @endif
                    </td>
                    <td>
                      <a href="{{ route('outputs.show', $approved->DOCUMENTO_INV) }}" class="btn btn-secondary btn-sm" title="ver" data-toggle="tooltip" data-placement="top"><i class="fas fa-eye"></i></a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <div class="p-5 text-center"><h2 class="text-muted">No hay documentos</h2></div>
              @endif
            </div>
          
            <div class="tab-pane fade" id="tab2-name" role="tabpanel" aria-labelledby="profile-tab">
            @if ($disapprovedOutputs->count() > 0)
              <!-- Add Content bellow -->
              <table class="table table-sm table-striped table-bordered m-0">
                <thead>
                  <tr>
                    <th>Estatus</th>
                    <th>Fecha Doc.</th>
                    <th>Documento</th>
                    <th>Usario</th>
                    <th>Referencia</th>
                    <th>Fecha de Aprovaci&oacute;n</th>
                    <th>Usuario Provaci&oacute;n</th>
                    <th><i class="fas fa-cog"></i></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($disapprovedOutputs as $disApproved)
                  <tr>
                    <td>
                    @switch($disApproved->APROBADO)
                      @case(null)
                        <i class="fas fa-check-circle text-danger"></i> No Aprobado
                        @break
                      @case('S')
                      <i class="fas fa-check-circle text-success"></i> Aprobado
                        @break
                      @default
                        'No Define'
                        @break
                    @endswitch  
                    </td>
                    <td>{{ date('d-m-Y', strtotime($disApproved->FECHA_DOCUMENTO)) }}</td>
                    <td>{{ $disApproved->DOCUMENTO_INV }}</td>
                    <td>{{ $disApproved->USUARIO }}</td>
                    <td>{{ $disApproved->REFERENCIA }}</td>
                    <td>
                    @if($disApproved->USUARIO_APRO != null)
                    {{ date('d-m-Y H:i:s', strtotime($disApproved->FECHA_HORA_APROB)) }}
                    @else
                    -
                    @endif
                    </td>
                    <td>
                    @if($disApproved->USUARIO_APRO != null)
                    {{ $disApproved->USUARIO_APRO }}
                    @else
                    -
                    @endif
                    </td>
                    <td>
                      <a href="{{ route('outputs.show', $disApproved->DOCUMENTO_INV) }}" class="btn btn-secondary btn-sm" title="ver" data-toggle="tooltip" data-placement="top"><i class="fas fa-eye"></i></a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <div class="p-5 text-center"><h2 class="text-muted">No hay documentos</h2></div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('post-body')
<!-- Modal -->
<div class="modal fade" id="new_sal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="#">Nueva Salida</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="POST" id="output-form">
          <!-- CSRF -->
          @csrf
          <!-- referencia -->
          <div class="form-group">
            <label for="referencia">Referencia (N&uacute;mero Interno)</label>
            <input type="text" class="form-control form-control-sm" data-inputmask="'mask': '**-***'" name="referencia" id="referencia"  value="{{ old('referencia') }}" placeholder="N.I" style="text-transform: uppercase;" required>
          </div>
          <!-- paquete_inventario -->
          <div class="form-group">
            <label for="paquete_inventario">Paquete de Salida</label>
            <select name="paquete_inventario" id="paquete_inventario" class="form-control form-control-sm" required>
              <option value="SAL">SAL</option>
              <option value="SAL1">SAL1</option>
            </select>
          </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="clearForm()" data-dismiss="modal"><i class="fas fa-window-close"></i> Cancelar</button>
        <button type="submit" class="btn btn-primary">Crear Salida</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('post-script')
<script src="{{ asset('js/mask.js') }}"></script>
<script>
  $(function() {
    $('#showModal').click(function() {
      $('#new_sal').modal('show')
    })

    Inputmask().mask(document.querySelectorAll("input"));
  })

  function clearForm() {
    let frm = document.getElementById('output-form')
    frm.reset()
  }
</script>
@endsection
