<!--

=========================================================
* Now UI Dashboard - v1.5.0
=========================================================

* Product Page: https://www.creative-tim.com/product/now-ui-dashboard
* Copyright 2019 Creative Tim (http://www.creative-tim.com)

* Designed by www.invisionapp.com Coded by www.creative-tim.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

-->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{asset('dashboard_asset/img/apple-icon.png')}}">
  <link rel="icon" type="image/png" href="{{asset('dashboard_asset/img/favicon.png')}}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Dashboard
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <!-- CSS Files -->
  <link href="{{asset('dashboard_asset/css/bootstrap.min.css')}}" rel="stylesheet" />
  <link href="{{asset('dashboard_asset/css/now-ui-dashboard.css?v=1.5.0')}}" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="{{asset('dashboard_asset/demo/demo.css')}}" rel="stylesheet" />
  @yield('links')
</head>
<body class="">
  <div class="wrapper ">
    <div class="sidebar" data-color="orange">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
    -->
      <div class="logo">
        <a href="/" class="simple-text logo-mini">
          MD
        </a>
        <a class="simple-text logo-normal">
          {{$user->name}}
        </a>
      </div>
      <div class="sidebar-wrapper" id="sidebar-wrapper">
        <ul class="nav">
          <li class="{{Route::is('dashboard') ? 'active' : ''}}">
            <a href="{{route('dashboard')}}">
              <i class="now-ui-icons design_app"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="{{Route::is('inventory.index') ? 'active' : ''}}">
            <a href="{{route('inventory.index')}}">
              <i class="now-ui-icons shopping_shop"></i>
              <p>Inventory</p>
            </a>
          </li>
          <li>
            @if(Auth::user()->hasRole(['super_admin']) || Auth::user()->hasRole(['admin']))
            <a href="{{route('orders')}}">
            @elseif(Auth::user()->hasRole(['employee']))
            <a href="{{route('employee.orders')}}">
            @endif
              <i class="now-ui-icons shopping_box"></i>
              <p>Orders</p>
            </a>
          </li>
          <li class="{{Route::is('show.customers') ? 'active' : ''}}">
            <a href="{{route('show.customers')}}">
              <i class="now-ui-icons users_single-02"></i>
              <p>Customers</p>
            </a>
          </li>
          <li class="{{Route::is('show.employees') ? 'active' : ''}}">
            <a href="{{route('show.employees')}}">
              <i class="now-ui-icons users_single-02"></i>
              <p>Employees</p>
            </a>
          </li>
          <li class="{{Route::is('roles.permissions') ? 'active' : ''}}">
            <a href="{{route('roles.permissions')}}">
              <i class="now-ui-icons objects_key-25"></i>
              <p>Permissions</p>
            </a>
          </li>
          <li class="{{Route::is('settings') ? 'active' : ''}}">
            <a href="{{route('settings')}}">
              <i class="now-ui-icons ui-1_settings-gear-63"></i>
              <p>Settings</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="main-panel" id="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent  bg-primary  navbar-absolute">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            Dashboard
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav">

              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdownAccountLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="now-ui-icons users_single-02"></i>
                  <p>
                    <span class="d-lg-none d-md-block">Account</span>
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownAccountLink">
                  <a class="dropdown-item" href="/">Website</a>
                  <a class="dropdown-item" href="{{ route('dashboard.messages') }}">Messages</a>
                  <form action="{{route('logout')}}" method="POST">
                    @csrf
                  <button class="dropdown-item">Logout</button>
                  </form>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      @yield('content')
      <footer class="footer">
        {{--
        <div class=" container-fluid ">
          <nav>
            <ul>
              <li>
                <a href="https://www.creative-tim.com">
                  Creative Tim
                </a>
              </li>
              <li>
                <a href="http://presentation.creative-tim.com">
                  About Us
                </a>
              </li>
              <li>
                <a href="http://blog.creative-tim.com">
                  Blog
                </a>
              </li>
            </ul>
          </nav>
          <div class="copyright" id="copyright">
            &copy; <script>
              document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
            </script>, Designed by <a href="https://www.invisionapp.com" target="_blank">Invision</a>. Coded by <a href="https://www.creative-tim.com" target="_blank">Creative Tim</a>.
          </div>
        </div>
        --}}
      </footer>
    </div>
  </div>
    <!--   Core JS Files   -->
  <script src="{{asset('dashboard_asset/js/core/jquery.min.js')}}"></script>
  <script src="{{asset('dashboard_asset/js/core/popper.min.js')}}"></script>
  <script src="{{asset('dashboard_asset/js/core/bootstrap.min.js')}}"></script>
  <script src="{{asset('dashboard_asset/js/plugins/perfect-scrollbar.jquery.min.js')}}"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chart JS -->
  <script src="{{asset('dashboard_asset/js/plugins/chartjs.min.js')}}"></script>
  <!--  Notifications Plugin    -->
  <script src="{{asset('dashboard_asset/js/plugins/bootstrap-notify.js')}}"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{asset('dashboard_asset/js/now-ui-dashboard.min.js?v=1.5.0')}}" type="text/javascript"></script><!-- Now Ui Dashboard DEMO methods, don't include it in your project! -->
  <script src="{{asset('dashboard_asset/demo/demo.js')}}"></script>
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      demo.initDashboardPageCharts();

    });
  </script>
  @yield('scripts')
</body>

</html>
