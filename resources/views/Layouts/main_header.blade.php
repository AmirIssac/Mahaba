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
        {{--
        <script src="https://kit.fontawesome.com/aa3f80bbad.js" crossorigin="anonymous"></script>
        --}}
        <style>
            body{
                background-color: #f5ebeb;
            }
            .primary-button{
                background-color: #622521;
                border: 1px solid #e4eef0;
                border-radius: 8px;
                color: #e4eef0;
                font-weight: bold;
                width: 100px;
                height: 35px;
            }
            a.primary-button:hover{
                text-decoration: none !important;
                color: white !important;
            }
            @media only screen and (max-width: 600px) {
                .my-sheep-content {
                    flex-direction: column;
                }
            }
         </style>
        @yield('links')
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="/"><b>خاروفي</b></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="/">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#!">Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('contact.us') }}">Contact us</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-user"></i>
                                @if(Auth::user())
                                    {{ Auth::user()->name }}
                                @else
                                    Guest
                                @endif
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @if(Auth::user())
                                    @if(Auth::user()->adminstrative())
                                        <li><a class="dropdown-item" href="{{route('dashboard')}}"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{route('my.profile')}}"><i class="fa-solid fa-address-card"></i> My profile</a></li>
                                    <li><hr class="dropdown-divider" /></li>
                                    <li><a class="dropdown-item" href="{{route('view.cart')}}"><i class="fa-solid fa-cart-shopping"></i> My cart</a></li>
                                    <li><a class="dropdown-item" href="{{route('my.orders')}}"><i class="fa-solid fa-box"></i> My orders</a></li>
                                    <li><hr class="dropdown-divider" /></li>
                                    <li class="dropdown-item">
                                        <form id="logout-form" action="{{route('logout')}}" method="POST">
                                            @csrf
                                            <a style="color: rgb(255, 34, 34)" href="javascript:$('#logout-form').submit();"class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket"></i> logout</a>
                                        </form>
                                    </li>
                                @else
                                <li><a class="dropdown-item" href="{{ route('login') }}"><i class="fa-solid fa-arrow-right-to-bracket"></i> Login</a></li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                    @if(Auth::user())
                    <a class="btn primary-button" href="{{ route('view.cart') }}">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        {{--
                        <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                        --}}
                    </a>
                    @else
                    <a class="btn primary-button" href="{{ route('view.guest.cart') }}">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                    </a>
                    @endif
                </div>
            </div>
        </nav>
        <!-- Header-->
        <header class="bg-prim py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white my-sheep-header">
                    <h1 class="display-4 fw-bolder text-prim">خاروفي</h1>
                    <div style="display: flex; justify-content: space-around;" class="my-sheep-content">
                        <a href="{{ route('index.by.category',1) }}">
                        <div style="display: flex; flex-direction: column">
                            <img src="{{ asset('images/mutton.jpg') }}" class="category_img" height="250px">
                            خاروف كامل مذبوح
                        </div>
                        </a>
                        <a href="{{ route('index.by.category',2) }}">
                        <div style="display: flex; flex-direction: column">
                            <img src="{{ asset('images/6waycut.jpg') }}" class="category_img" height="250px">
                            مقطعات الخاروف
                        </div>
                        </a>
                    </div>
                    <p class="lead fw-normal text-white-50 mb-0"> Dabbagh | دباغ</p>
                </div>
            </div>
        </header>
        @yield('body')
        <!-- Footer-->
        <footer class="py-2 bg-prim">
            <div class="container"><p class="m-0 text-center text-white">Dabbagh</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- jquery -->
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <!-- Core theme JS-->
        <script src="{{asset('js/scripts.js')}}"></script>
      @yield('scripts')
    </body>
</html>
