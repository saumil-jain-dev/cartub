<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login - Car tub | Easy Wash, Anytime</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap"
        rel="stylesheet"><!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/fontawesome.css') }}"><!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/icofont.css') }}"><!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/themify.css') }}"><!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/flag-icon.css') }}"><!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/feather-icon.css') }}">
    <!-- Plugins css start-->
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
    <!-- App css-->
    <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/css/vendors/toastr.min.css') }}" type="text/css" />
</head>

<body>
    <!-- login page start-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <div class="login-card login-dark">
                    <div>
                        <div><a class="logo text-start" href="index"><img class="img-fluid for-light"
                                    src="{{ asset('assets/images/logo.png') }}" alt="looginpage"><img
                                    class="img-fluid for-dark" src="{{ asset('assets/images/logo.png') }}"
                                    alt="looginpage"></a></div>
                        <div class="login-main">
                            <form class="theme-form" method="post" action="{{ route('login') }}">
                                @csrf
                                <h4>Sign in to account</h4>
                                <p>Enter your email & password to login</p>
                                <div class="form-group"><label class="col-form-label">Email Address</label><input
                                        class="form-control" type="email"  placeholder="test@gmail.com" name="email">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="form-group"><label class="col-form-label">Password</label>
                                    <div class="form-input position-relative"><input class="form-control"
                                            type="password" name="password"  placeholder="*********">
                                        <div class="show-hide"><span class="show"> </span></div>
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <div class="form-check"><input class="checkbox-primary form-check-input"
                                            id="checkbox1" type="checkbox"><label class="text-muted form-check-label"
                                            for="checkbox1">Remember
                                            password</label></div><a class="link" href="forgot-password">Forgot
                                        password?</a>
                                    <div class="text-end"><button class="btn btn-primary btn-block w-100 mt-3"
                                            type="submit">Sign
                                            in</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- latest jquery-->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script><!-- Bootstrap js-->
        <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script><!-- feather icon js-->
        <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
        <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script><!-- scrollbar js-->
        <!-- Sidebar jquery-->
        <script src="{{ asset('assets/js/config.js') }}"></script><!-- Plugins JS start-->
        <!-- Plugins JS Ends-->
        <!-- Theme js-->
        <script src="{{ asset('assets/js/script.js') }}"></script>
        <script src="{{ asset('assets/js/script1.js') }}"></script>
        <script src="{{ asset('assets/js/toastr.min.js') }}"></script><!-- login js-->
        @if (\Session::has('success') || \Session::has('error') || \Session::has('info') || \Session::has('warning') || !empty($errors->all()))
            <script>
                toastr.options = {
                    closeButton: true,
                    debug: false,
                    newestOnTop: false,
                    progressBar: true,
                    positionClass: 'toast-bottom-right', // You can change this to your preferred position
                    preventDuplicates: false,
                    onclick: null,
                    showDuration: '300', // Duration of the fade-in animation in milliseconds
                    hideDuration: '1000', // Duration of the fade-out animation in milliseconds
                    timeOut: '5000', // Time the notification is displayed in milliseconds (5 seconds in this case)
                    extendedTimeOut: '1000', // Extra time to display the notification if a user hovers over it in milliseconds
                    showEasing: 'swing', // Easing animation for show
                    hideEasing: 'linear', // Easing animation for hide
                    showMethod: 'fadeIn', // Animation method for show
                    hideMethod: 'fadeOut' // Animation method for hide
                };

                @foreach(['success', 'error', 'info', 'warning'] as $type)
                    @if (\Session::has($type))
                        toastr.{{ $type }}("{{ session($type) }}");
                    @endif
                @endforeach

                @if (!empty($errors->all()))
                    @foreach ($errors->all() as $error)
                        toastr.error("{{ $error }}");
                    @endforeach
                @endif
            </script>
            
        @endif
    </div>
</body>

</html>