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
                    <form method="POST" action="{{ route('register') }}" class="login100-form">
                        @csrf
                        <div class="divider d-flex align-items-center my-4">
                      <p class="text-center fw-bold mx-3 mb-0">Sign up</p>
                    </div>

                    <div class="form-outline">
                        @error('first_name')
						    <div style="color: red; font-weight:bold;">{{ $message }}</div>
					    @enderror
                      <label class="form-label" for="form3Example3">First Name</label>
                      <input type="text" id="form3Example3" name="first_name" class="form-control form-control-lg"
                      />
                      @error('last_name')
						    <div style="color: red; font-weight:bold;">{{ $message }}</div>
					  @enderror
                      <label class="form-label" for="form3Example3">Last Name</label>
                        <input type="text" id="form3Example3" name="last_name" class="form-control form-control-lg"
                         />
                    </div>

                    <div class="form-outline">
                        @error('email')
						    <div style="color: red; font-weight:bold;">{{ $message }}</div>
					    @enderror
                        <label class="form-label" for="form3Example3">Email address</label>
                        <input type="email" id="form3Example3" name="email" class="form-control form-control-lg"
                          placeholder="Enter a valid email address" />
                          @error('password')
                          <div style="color: red; font-weight:bold;">{{ $message }}</div>
                          @enderror
                          <label class="form-label" for="form3Example4">Password</label>
                          <input type="password" id="form3Example4" name="password" class="form-control form-control-lg"
                            placeholder="Enter password" />
                      </div>

                      <div class="form-outline">
                        @error('phone')
                        <div style="color: red; font-weight:bold;">{{ $message }}</div>
                        @enderror
                        <label class="form-label" for="form3Example3">Phone</label>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1">+971</span>
                            </div>
                            <input type="text" class="form-control" name="phone" placeholder="5xxxxxxxx" aria-label="Username" aria-describedby="basic-addon1">
                          </div>
                      </div>

                    <div class="text-center text-lg-start mt-2">
                      <button type="submit" class="btn btn-success btn-lg"
                        style="padding-left: 2.5rem; padding-right: 2.5rem; color:white;">Sign up</button>
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
