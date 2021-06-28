<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Custom css inclusion -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- toastr css library -->
    <link href="{{ asset('css/toastr.css') }}" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Post head content  -->
    @yield('head')
</head>
<body @yield('body-properties')>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                  <img src="{{ asset('img/sc-icon-sm.png')}}" class="brand" alt="">
                  {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                      @guest

                      @else
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbar_dropdown_menu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Pedidos
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbar_dropdown_menu1">
                          <a class="dropdown-item" href="{{ route('pedidos.index') }}"><span class="badge badge-warning">{{ auth()->user()->pedidos->where('estado', 'en proceso')->count() }}</span> En Proceso </a>
                          <a class="dropdown-item" href="#"><span class="badge badge-warning">{{ auth()->user()->pedidos->where('estado', 'aprovado')->count() }}</span> Aprovados </a>
                          <a class="dropdown-item" href="#"><span class="badge badge-warning">{{ auth()->user()->pedidos->where('estado', 'rechazado')->count() }}</span> Rechazados </a>
                          <a class="dropdown-item" href="#"><span class="badge badge-warning">{{ auth()->user()->pedidos->where('estado', 'entregado')->count() }}</span> Entregados</a>
                          <a class="dropdown-item" href="#"><span class="badge badge-warning">{{ auth()->user()->pedidos->where('estado', 'expirado')->count() }}</span> Expirados </a>
                        </div>
                      </li>
                      @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> {{ __('Iniciar Sesión') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li> -->
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('Salir') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('carrito.index') }}"><i class="fas fa-2x fa-shopping-cart"></i><sub class="badge badge-warning">{{ auth()->user()->carrito()->count() }}</sub> </a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @yield('post-script')
</body>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@toastr_render
@yield('post-body')
</html>
