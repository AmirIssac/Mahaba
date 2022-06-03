<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        @yield('meta')
        <title>@yield('title')</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{asset('css/styles.css')}}" rel="stylesheet" />
        <script src="https://kit.fontawesome.com/aa3f80bbad.js" crossorigin="anonymous"></script>
        @yield('links')
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="#!"><b>خاروفي</b></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#!">About</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-user"></i></a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @if(Auth::user())
                                    @if(Auth::user()->adminstrative())
                                        <li><a class="dropdown-item" href="{{route('dashboard')}}">Dashboard</a></li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{route('my.profile')}}">My profile</a></li>
                                    <li><hr class="dropdown-divider" /></li>
                                    <li><a class="dropdown-item" href="{{route('view.cart')}}">My cart</a></li>
                                    <li><a class="dropdown-item" href="{{route('my.orders')}}">My orders</a></li>
                                    <li><a class="dropdown-item" href="#" style="color: #ffa909">{{$point}} point</a></li>
                                    <li><hr class="dropdown-divider" /></li>
                                    <li class="dropdown-item">
                                        <form id="logout-form" action="{{route('logout')}}" method="POST">
                                            @csrf
                                            <a style="color: rgb(255, 34, 34)" href="javascript:$('#logout-form').submit();">logout</a>
                                        </form>
                                    </li>
                                @else
                                <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                                @endif
                            </ul>
                        </li>
                    </ul>

                            <button class="btn btn-outline-dark" type="submit">
                                <i class="bi-cart-fill me-1"></i>
                                Cart
                                <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                            </button>
                </div>
            </div>
        </nav>
        @yield('body')
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{asset('js/scripts.js')}}"></script>
      @yield('scripts')
    </body>
</html>
