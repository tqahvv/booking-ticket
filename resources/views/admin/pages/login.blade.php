<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Trang đăng nhập Admin</title>

    <!-- Bootstrap -->
    <link href="{{ asset('assets/admin/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('assets/admin/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('assets/admin/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- Animate.css') }} -->
    <link href="{{ asset('assets/admin/vendors/animate.css/animate.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('assets/admin/build/css/custom.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <style>
        .alert-carousel-wrapper {
            margin-bottom: 15px;
        }

        .alert-item {
            padding: 12px 18px;
            border-radius: 6px;
            font-size: 15px;
            color: #fff;
        }

        .alert-item.error {
            background: #e74c3c;
        }

        .alert-item.success {
            background: #27ae60;
        }

        .owl-carousel .owl-stage {
            display: flex;
            align-items: center;
        }
    </style>

</head>

<body class="login">
    <div>
        <a class="hiddenanchor" id="signup"></a>
        <a class="hiddenanchor" id="signin"></a>

        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    @if ($errors->any() || session('error') || session('success'))
                        <div class="alert-carousel-wrapper">
                            <div class="owl-carousel alert-carousel">

                                @foreach ($errors->all() as $error)
                                    <div class="alert-item error">{{ $error }}</div>
                                @endforeach

                                @if (session('error'))
                                    <div class="alert-item error">{{ session('error') }}</div>
                                @endif

                                @if (session('success'))
                                    <div class="alert-item success">{{ session('success') }}</div>
                                @endif

                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.login.post') }}" method="POST">
                        @csrf
                        <h1>Đăng nhập</h1>
                        <div>
                            <input type="text" class="form-control" name="email" placeholder="Email"
                                value="{{ old('email') }}" required />
                        </div>
                        <div>
                            <input type="password" autocomplete="off" class="form-control" name="password"
                                placeholder="Mật khẩu" required />
                        </div>
                        <div>
                            <button type="submit" class="btn btn-default submit" style="border: solid 1px #ccc">Đăng
                                nhập</button>
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator">
                            <div class="clearfix"></div>
                            <br />

                            <div>
                                <h1><i class="fa fa-paw"></i> XEVENHA</h1>
                                <p>Chào mừng bạn đến với trang quản trị của XEVENHA! Hãy đăng nhập để vào trang quản
                                    trị nhé!!!</p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($('.alert-carousel .alert-item').length > 0) {
                $('.alert-carousel').owlCarousel({
                    items: 1,
                    loop: true,
                    autoplay: true,
                    autoplayTimeout: 3000,
                    autoplayHoverPause: true,
                    animateOut: 'fadeOut',
                    animateIn: 'fadeIn',
                    margin: 10,
                });

                setTimeout(() => {
                    $('.alert-carousel-wrapper').fadeOut(600);
                }, 5000);
            }
        });
    </script>

</body>

</html>
