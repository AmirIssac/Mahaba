<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="login_v1/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_v1/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_v1/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_v1/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="login_v1/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_v1/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_v1/css/util.css">
	<link rel="stylesheet" type="text/css" href="login_v1/css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="img/logo.png" alt="IMG">
				</div>
                <form method="POST" action="{{ route('register') }}" class="login100-form">
                    @csrf
					<span class="login100-form-title">
						 Sign up
					</span>
					@error('first_name')
						    <div style="color: red; font-weight:bold;">{{ $message }}</div>
					@enderror
                    <div class="wrap-input100">
						<input class="input100" type="text" name="first_name" placeholder="First Name">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>
					@error('last_name')
						    <div style="color: red; font-weight:bold;">{{ $message }}</div>
					@enderror
                    <div class="wrap-input100">
						<input class="input100" type="text" name="last_name" placeholder="Last Name">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>
					@error('email')
						    <div style="color: red; font-weight:bold;">{{ $message }}</div>
					@enderror
					<div class="wrap-input100">
						<input class="input100" type="email" name="email" placeholder="Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>
					@error('password')
					<div style="color: red; font-weight:bold;">{{ $message }}</div>
					@enderror
					<div class="wrap-input100">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					{{--
                    <div class="wrap-input100">
						<input class="input100" type="text" name="phone" placeholder="Phone">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-phone" aria-hidden="true"></i>
						</span>
					</div>--}}
					{{--
					<div class="wrap-input100">
					<div class="input-group">
						<span class="input-group-addon">+971</span>
						<input type="text" name="phone" placeholder="Phone">
					</div>
					</div>
					--}}
					@error('phone')
					<div style="color: red; font-weight:bold;">{{ $message }}</div>
					@enderror
					<div class="wrap-input100">
						<input class="input100" type="text" name="phone" placeholder="500000000">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							+971
						</span>
					</div>
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Sign up
						</button>
					</div>
                    {{--
					<div class="text-center p-t-12">
						<span class="txt1">
							Forgot
						</span>
						<a class="txt2" href="#">
							Username / Password?
						</a>
					</div>
                    --}}
                    {{--
					<div class="text-center p-t-136">
						<a class="txt2" href="#">
							Create your Account
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
                    --}}
				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="login_v1/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="login_v1/vendor/bootstrap/js/popper.js"></script>
	<script src="login_v1/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="login_v1/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="login_v1/vendor/tilt/tilt.jquery.min.js"></script>
	<script>
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="login_v1/js/main.js"></script>

</body>
</html>