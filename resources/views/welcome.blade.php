@extends('layouts.login_layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>
<script src="{{ asset('Login_v1/vendor/tilt/tilt.jquery.min.js') }}"></script>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="login100-pic js-tilt" data-tilt>
                <img src="Login_v1/images/img-02.png" alt="IMG">
            </div>

            <form action="{{ route('connectLogin') }}" class="login100-form validate-form" method="POST">
                @csrf
                <span class="login100-form-title">
                    <b>เข้าสู่ระบบเพื่อใช้งาน</b>
                </span>

                @error('email')
                <div class="col-md-12">
                    <span class="text-danger" role="alert">
                        <strong>{{ 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง' }}</strong>
                    </span>
                </div>
                @enderror
                <div class="wrap-input100 validate-input" data-validate="Email is required">
    <input id="email" class="input100 @error('email') is-invalid @enderror" 
        type="email" name="email" value="{{ old('email') }}" 
        placeholder="Email" required autocomplete="email" autofocus>
    <span class="focus-input100"></span>
    <span class="symbol-input100">
        <i class="fa fa-user" aria-hidden="true"></i>
    </span>
</div>


                @error('password')
                <div class="col-md-12">
                    <span role="alert">
                        <strong>{{ 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง' }}</strong>
                    </span>
                </div>
                @enderror

                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input id="password" class="input100 @error('password') is-invalid @enderror" 
                        type="password" name="password" placeholder="Password" 
                        required autocomplete="current-password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="container-login100-form-btn">
                    <button id="login-btn" type="submit" class="login100-form-btn">Login</button>
                </div>

                @if (Route::has('password.request'))
                <div class="text-center p-t-12">
                    <span class="txt1">Forgot</span>
                    <a class="txt2" href="{{ route('password.request') }}">
                        Username / Password?
                    </a>
                </div>
                @endif

                <div class="text-center p-t-136">
                    <a class="txt2" href="#"></a>
                </div>
            </form>
       
                    </div>
                    <div class="col-lg-6 text-center">
                        <div class="container">
                            <small class="m-l-5 " aria-hidden="true">Copyrights © 2019 All Rights Reserved by GYPMAN TECH COMPANY LIMITED.</small>
                        </div>
                    </div>
                </div>
            </div>


            <script>
            $(document).ready(function() {
                $('#login-btn').click(function(){
                    $('#overlay').fadeIn().delay(2000).fadeOut();
                });
            });
            </script>

        @endsection
