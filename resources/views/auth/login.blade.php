<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login - SIMBAK</title>



    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('template/images/logo-simbak1.png') }}">
    <link rel="stylesheet" href="{{ asset('template-login/dist/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template-login/dist/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template-login/dist/assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('template-login/dist/assets/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('template-login/dist/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template-login/dist/assets/css/slicknav.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template-login/dist/assets/css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('template-login/dist/assets/css/default-css.css') }}">
    <link rel="stylesheet" href="{{ asset('template-login/dist/assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('template-login/dist/assets/css/responsive.css') }}">
</head>

<body>
    <div id="preloader">
        <div class="loader"></div>
    </div>

    <div class="login-area login-s2">
        <div class="container">
            <div class="login-box ptb--100">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="login-form-head">
                        <h4>Log In</h4>
                        <p>Silakan login untuk melanjutkan</p>
                    </div>
                    <div class="login-form-body">
                        {{-- Email --}}
                        <div class="form-gp">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autofocus>
                            <i class="ti-email"></i>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="form-gp">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" required>
                            <i class="ti-lock"></i>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Remember Me --}}
                        <div class="row mb-4 rmber-area">
                            <div class="col-6">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="remember_me"
                                        name="remember">
                                    <label class="custom-control-label" for="remember_me">Remember Me</label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="submit-btn-area">
                            <button id="form_submit" type="submit">Login <i class="ti-arrow-right"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('template-login/dist/assets/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('template-login/dist/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('template-login/dist/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('template-login/dist/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('template-login/dist/assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('template-login/dist/assets/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('template-login/dist/assets/js/jquery.slicknav.min.js') }}"></script>
    <script src="{{ asset('template-login/dist/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('template-login/dist/assets/js/scripts.js') }}"></script>
</body>

</html>
