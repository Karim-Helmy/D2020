<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login Future</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="shortcut icon" type="image/x-icon" href="{{ asset('backend/app-assets/images/ico/favicon.ico') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('homepage/login/vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('homepage/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('homepage/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('homepage/login/vendor/animate/animate.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('homepage/login/vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('homepage/login/vendor/animsition/css/animsition.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('homepage/login/vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('homepage/login/vendor/daterangepicker/daterangepicker.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('homepage/login/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('homepage/login/css/main.css')}}">
<!--===============================================================================================-->
 @if (session()->get('locale') == "ar")
     <style>
     @font-face {
         font-family: my;
         src:url('backend/assets/fonts/din-next/regular/DinNextRegular.eot');
         src:url('backend/assets/fonts/din-next/regular/DinNextRegular.eot') format('embedded-opentype'),url('backend/assets/fonts/din-next/regular/DinNextRegular.woff2') format('woff2'),url('backend/assets/fonts/din-next/regular/DinNextRegular.woff') format('woff'),url('backend/assets/fonts/din-next/regular/DinNextRegular.ttf') format('truetype'),url('backend/assets/fonts/din-next/regular/DinNextRegular.svg#DinNextRegular') format('svg');

     }
     body{
         font-family: my !important;
         direction: rtl;
         text-align: right;
     }
     .login100-form-title,.label-checkbox100,.input100,.login100-form-btn,.label-input100{
         font-family: my;
     }
     .label-input100{
         padding-left:0px;
         padding-right:24px;
    }
    .label-checkbox100{
        padding-right:26px;
    }
    .label-checkbox100::before{
        left:67px;
    }
 </style>
@endif
<style>
.login100-form {
    width: 30% !important;
}
.login100-more {
	width: calc(100% - 30%);
}

</style>
</head>
<body style="background-color: #666666;">

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form"  method="post" action="{{url('/sessionstore')}}" style="background:linear-gradient(to right bottom, #ff7523, #5f6bd5);">
                    @csrf
					<a href="{{ url('/home') }}"><img class="img-responsive" src="{{ asset('homepage/login/images/logo-black.png')}}" style="height:100px; width:256px; display:block; margin:auto;" /></a>
					<br /><br /><br />
                    @if ($errors->count())
                        <div class="alert alert-danger" style="width:100%;">
                            <ul>
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session()->has('success'))
                        <div class="alert alert-success" style="width:100%;">{{ session()->get('success') }}</div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger" style="width:100%;">{{ session()->get('error') }}</div>
                    @endif
					<span class="login100-form-title p-b-43">
						{{ trans('login.login') }}
					</span>


					<div class="wrap-input100 validate-input" data-validate = "Valid username is required">
						<input class="input100" type="text" name="username" value="{{ old('username') }}">
						<span class="focus-input100"></span>
						<span class="label-input100">{{ trans('admin.username') }}</span>
					</div>


					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<input class="input100" type="password" name="password"  value="{{ old('password') }}">
						<span class="focus-input100"></span>
						<span class="label-input100">{{ trans('login.password') }}</span>
					</div>

					<div class="flex-sb-m w-full p-t-3 p-b-32">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox"  name="remember" {{ old('remember') ? 'checked' : '' }}>
							<label class="label-checkbox100" for="ckb1">
								{{ trans('login.remember') }}
							</label>
						</div>
					</div>


					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							{{ trans('login.login') }}
						</button>
					</div>


				</form>

				<div class="login100-more" style="background-image: url('homepage/login/images/image-002.png');">
				</div>
			</div>
		</div>
	</div>





<!--===============================================================================================-->
	<script src="{{ asset('homepage/login/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('homepage/login/vendor/animsition/js/animsition.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('homepage/login/vendor/bootstrap/js/popper.js')}}"></script>
	<script src="{{ asset('homepage/login/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('homepage/login/vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('homepage/login/vendor/daterangepicker/moment.min.js')}}"></script>
	<script src="{{ asset('homepage/login/vendor/daterangepicker/daterangepicker.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('homepage/login/vendor/countdowntime/countdowntime.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('homepage/login/js/main.js')}}"></script>

</body>
</html>
