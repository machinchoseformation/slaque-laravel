<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Slaque</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/icon.png')  }}">
</head>
<body>
    <header>
        <div class="container">
            <a class="logo" href="{{ url('/') }}"><img src="{{ asset('img/logo.svg')  }}" alt="Slaque logo">Slaque</a>
            <nav>
                <!-- Authentication Links -->
                @guest
                        <a href="{{ route('login') }}">Connexion</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Inscription</a>
                        @endif
                @else
                    <a href="{{ route('group_list')  }}">Mes groupes</a>
                    <a href="{{ route('group_create')  }}">Créer un groupe</a>

                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Déconnexion ({{ Auth::user()->name }})
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" >
                        @csrf
                    </form>

            @endguest
            </nav>
        </div>
    </header>

    <div class="flash-message">
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
        @endif
    </div> <!-- end .flash-message -->

    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>
    <footer>
        <div class="container">
            &copy; {{date("Y")}} | Slaque
        </div>
    </footer>

    @yield('js')
</body>
</html>
