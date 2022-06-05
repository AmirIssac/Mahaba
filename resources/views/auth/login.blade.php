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
        <link href="{{asset('css/login.css')}}" rel="stylesheet" />
        <script src="https://kit.fontawesome.com/aa3f80bbad.js" crossorigin="anonymous"></script>
        @yield('links')
    </head>
    <body>
        <section class="vh-100">
            <div class="container-fluid h-custom">
              <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                  <img src="{{ asset('images/mutton.jpg') }}"
                    class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                    <div class="divider d-flex align-items-center my-4">
                      <p class="text-center fw-bold mx-3 mb-0">Sign in</p>
                    </div>

                    <!-- Email input -->
                    <div class="form-outline mb-4">
                      <label class="form-label" for="form3Example3">Email address</label>
                      <input type="email" id="form3Example3" name="email" class="form-control form-control-lg"
                        placeholder="Enter a valid email address" />
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-3">
                      <label class="form-label" for="form3Example4">Password</label>
                      <input type="password" id="form3Example4" name="password" class="form-control form-control-lg"
                        placeholder="Enter password" />
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                      <!-- Checkbox -->
                      <div class="form-check mb-0">
                        <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
                        <label class="form-check-label" for="form2Example3">
                          Remember me
                        </label>
                      </div>
                      <a href="#!" class="text-body">Forgot password?</a>
                    </div>
                    <div class="text-center text-lg-start mt-4 pt-2">
                      <button class="btn btn-prim btn-lg"
                        style="padding-left: 2.5rem; padding-right: 2.5rem; color:white;">Login</button>
                      <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="{{ route('sign.up') }}"
                          class="link-danger">Register</a></p>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div
              class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-prim">
              <!-- Copyright -->
              <div class="text-white mb-3 mb-md-0">
                خاروفي
              </div>
              <!-- Copyright -->

              <!-- Right -->
              <div>
                <a href="#!" class="text-white me-4">
                  <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#!" class="text-white me-4">
                  <i class="fab fa-twitter"></i>
                </a>
                <a href="#!" class="text-white me-4">
                  <i class="fab fa-google"></i>
                </a>
                <a href="#!" class="text-white">
                  <i class="fab fa-linkedin-in"></i>
                </a>
              </div>
              <!-- Right -->
            </div>
          </section>
</body>
</html>
