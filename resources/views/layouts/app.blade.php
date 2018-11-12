<!doctype html>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="url" content="{{ config('app.url') }}">

    <!-- favicon -->
    <link rel="icon" type="image/png" href="{{ asset('scc_favicon_32x32.png') }}" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/custom.css') }}">
    @yield('head')

    <title>Buscador</title>
  </head>
  <body @yield('body-properties')>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="{{ url('/') }}"><img class="logo" src="{{ asset('img/sc-min-icon.png') }}"></a>

      <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <li class="nav-item active">
            <a class="nav-link" href="{{ url('/') }}">Inicio <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('search') }}">Buscar</a>
          </li>
        </ul>
      </div>
    </nav>
    <br>

    @yield('content')

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ asset('/js/jquery.js') }}"></script>
    <script src="{{ asset('/js/popper.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>

    <script type="text/javascript">
      $(function(){
        $('[data-toggle="tooltip"]').tooltip()

        var url = document.querySelector('meta[name="url"]').content;
        var i = 0;

        setInterval(function() {
          i++

          if (i == (60 * 10 )) {
            i = 0
            window.location = url
          }

          window.addEventListener('mousemove', function(){ i = 0});
          window.addEventListener('dblclick', function(){ i = 0});
          window.addEventListener('click', function(){ i = 0});
          window.addEventListener('keydown', function(){ i = 0});
        }, 1000);
      })
    </script>
    @yield('post-script')
  </body>
</html>
