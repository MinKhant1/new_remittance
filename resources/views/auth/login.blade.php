{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}


<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="frontend/login/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="frontend/login/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="frontend/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="frontend/login/fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="frontend/login/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="frontend/login/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="frontend/login/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="frontend/login/vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="frontend/login/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="frontend/login/css/util.css">
	<link rel="stylesheet" type="text/css" href="frontend/login/css/main.css">
<!--===============================================================================================-->


<style>
    .profile_img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    margin: 10px auto;
    border: 10px solid #ccc;
    border-radius: 50%;
    display:flex;
}
</style>
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('frontend/login/images/bg-01.jpg');">
			<div class="wrap-login100">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <a href="{{url('/')}}">
                        @php
                            $company=App\Models\Company::find(1);
                            $company_image;
                            if($company)
                            {
                                $company_image=$company->image;
                                
                            }

                        @endphp
                    
                    @if($company && $company_image)
              
                         <img class="profile_img" src="{{'/frontend/images/'.$company_image}}" alt="company image">
                    @else
                    
                    <span class="login100-form-logo">                       
                        <i class="zmdi zmdi-landscape"></i>
                    </span>
                    @endif
                    </a>

					<span class="login100-form-title p-b-34 p-t-27">
						Log in
					</span>

					@if (count($errors) > 0)
               		 <div class="alert alert-danger">
                		  <ul>
             		       @foreach ($errors->all() as $error)
             		         <li>{{$error}}</li>
            		        @endforeach
            		      </ul>
            		    </div>
            		  @endif

					  @if (Session::has('status'))
             			 <div class="alert alert-danger">
             			   {{Session::get('status')}}
             			 </div>
                      @endif

                      <div class="wrap-input100 validate-input" data-validate = "Enter Name">
						<input class="input100" type="name" name="name" placeholder="Name">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>				

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Login
						</button>
					</div>

					<div class="text-center p-t-90">
						<!--<a class="txt1" href="{{url('/signup')}}">-->
						<!--	Don't have an account? Sign up-->
						<!--</a>-->
                        <div class="text-right" style="padding-top:10%; ">
                            <a class="txt1" style="font-size:60% ;" href="{{url('/signup')}}">
                              Developed by Sysfotech
                            </a>
                        </div>
					</div>
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="frontend/login/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="frontend/login/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="frontend/login/vendor/bootstrap/js/popper.js"></script>
	<script src="frontend/login/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="frontend/login/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="frontend/login/vendor/daterangepicker/moment.min.js"></script>
	<script src="frontend/login/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="frontend/login/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="frontend/login/js/main.js"></script>

</body>
</html>