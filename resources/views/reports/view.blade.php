@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card m-0 p-0">
                    <div class="card-header bg-dark text-light">
                        reporte
                    </div>
                    <div class="card-body">
                    <iframe src="{{ $report_url }}" frameborder="0" class="embed-responsive embed-responsive-16by9" gesture="media"  allow="encrypted-media" allowfullscreen height="600px" style="margin: 0;">
                        Your Browser dont support iFrame
                    </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection