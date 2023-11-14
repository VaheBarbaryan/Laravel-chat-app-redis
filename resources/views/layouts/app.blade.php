<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/hyper-config.js') }}"></script>

    <!-- App css -->
    <link href="{{ asset('/css/app-saas.min.css') }}" rel="stylesheet" type="text/css"
        id="app-style" />

    <!-- Icons css -->
    <link href="{{ asset('/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    @stack('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if(Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if(Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <div class="dropdown float-end">
                                <a href="#" id="accountBtn" class="dropdown-toggle arrow-none card-drop"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ \Illuminate\Support\Facades\Auth::user()->name }} <i
                                        class="ri  ri-arrow-down-s-fill"></i>
                                </a>
                                <div id="dropdown-menu" class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <a href="{{ route('logout') }}" class="dropdown-item"
                                        onclick="Logout()">Logout</a>

                                    <form id="logout-form" action="{{ route('logout') }}"
                                        method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    <!-- item-->
                                </div>
                            </div>
                            {{-- <li class="nav-item dropdown"> --}}
                            {{-- <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre> --}}
                            {{-- {{ Auth::user()->name }}--}}
                            {{-- </a> --}}

                            {{-- <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown"> --}}
                            {{-- <a class="dropdown-item" href="{{ route('logout') }}"--}}
                            {{-- onclick="event.preventDefault(); --}}
                            {{-- document.getElementById('logout-form').submit();"> --}}
                            {{-- {{ __('Logout') }}--}}
                            {{-- </a> --}}

                            {{-- <form id="logout-form" action="{{ route('logout') }}"
                            method="POST" class="d-none">--}}
                            {{-- @csrf --}}
                            {{-- </form> --}}
                            {{-- </div> --}}
                            {{-- </li> --}}
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
<!-- Vendor js -->
<script src="{{ asset('/js/vendor.min.js') }}"></script>
<script>
    function Logout() {
        event.preventDefault();
        document.getElementById('logout-form').submit();
    }

</script>
<!-- App js -->
@auth
    <script>
        window.addEventListener("load", eventWindowLoaded, false);

        function eventWindowLoaded() {
            let accountBtn = document.querySelector('#accountBtn');
            let dropdownMenu = document.querySelector('#dropdown-menu');
            accountBtn.addEventListener('click', () => {
                console.log("click!!");
                accountBtn.classList.toggle('show');
                dropdownMenu.classList.toggle('show');
                dropdownMenu.style.position = dropdownMenu.style.position !== "absolute" ? 'absolute' : '';
                dropdownMenu.style.margin = dropdownMenu.style.margin !== "0px" ? "0px" : '';
                dropdownMenu.style.transform = dropdownMenu.style.transform !== "translate(0px, 31px)" ?
                    "translate(0px, 31px)" : "";
                accountBtn.ariaExpanded = accountBtn.ariaExpanded === "true" ? "false" : "true";
            });
        }

    </script>
@endauth

@stack('scripts')

</html>
