<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<title>{{ config('websetting.weblogin') }}</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
<!--===============================================================================================-->
	<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

<!--===============================================================================================-->
    <link href="{{ asset('Login_v1/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
<!--===============================================================================================-->
    <link href="{{ asset('Login_v1/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
<!--===============================================================================================-->
    <link href="{{ asset('Login_v1/vendor/animate/animate.css') }}" rel="stylesheet" type="text/css">
<!--===============================================================================================-->
    <link href="{{ asset('Login_v1/vendor/css-hamburgers/hamburgers.min.css') }}" rel="stylesheet" type="text/css">
<!--===============================================================================================-->
    <link href="{{ asset('Login_v1/vendor/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
<!--===============================================================================================-->
    <link href="{{ asset('Login_v1/css/util.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('Login_v1/css/main.css') }}" rel="stylesheet" type="text/css">
<!--===============================================================================================-->
</head>
<body>
        @yield('content')
<!--===============================================================================================-->
    <script src="{{ asset('Login_v1/vendor/jquery/jquery-3.2.1.min.js') }}" defer></script>
<!--===============================================================================================-->
    <script src="{{ asset('Login_v1/vendor/bootstrap/js/popper.js') }}" defer></script>
    <script src="{{ asset('Login_v1/vendor/bootstrap/js/bootstrap.min.js') }}" defer></script>
<!--===============================================================================================-->
    <script src="{{ asset('Login_v1/vendor/select2/select2.min.js') }}" defer></script>
<!--===============================================================================================-->
    <script src="{{ asset('Login_v1/vendor/tilt/tilt.jquery.min.js') }}" defer></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
    <script src="{{ asset('Login_v1/js/main.js') }}" defer></script>

</body>
</html>
