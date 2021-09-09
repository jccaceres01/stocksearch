@extends('layouts.app')

@section('content')
<output-component :output="{{ $output }}" softland_user="{{ auth()->user()->softlandUser->USUARIO }}"></output-component>
@endsection

@section('post-script')
<script>
$(function() {
    
  })
</script>
@endsection