<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ asset('assets/client/img/fav.png') }}">
    <meta name="author" content="colorlib">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/client/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/linearicons.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


</head>

<body>
    @include('client.partials.header')

    @if (session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if (session('warning'))
        <div class="container mt-3">
            <div class="alert alert-warning text-center">
                {{ session('warning') }}
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @yield('content')

    @include('client.partials.footer')

    <div id="chat-widget">

        <div id="chat-toggle">
            <i class="bi bi-chat-dots-fill"></i>
        </div>

        <div id="chat-box" class="hidden">
            <div id="chat-header">
                <span>Hỗ trợ đặt vé</span>
                <button id="chat-close">&times;</button>
            </div>

            <div id="chat-messages"></div>

            <div id="chat-input">
                <input type="text" id="message-input" placeholder="Nhập câu hỏi..." />
                <button id="send-btn">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </div>

    </div>

    <!-- jQuery luôn đứng đầu -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Các plugin phụ thuộc jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="{{ asset('assets/client/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/client/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/jquery.ajaxchimp.min.js') }}"></script>

    <!-- Sau đó mới là Bootstrap và các JS khác -->
    {{-- <script src="{{ asset('assets/client/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/vendor/bootstrap.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/client/js/easing.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/hoverIntent.js') }}"></script>
    <script src="{{ asset('assets/client/js/superfish.min.js') }}"></script>

    <!-- Cuối cùng là main.js (chứa đoạn OwlCarousel khởi tạo) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/client/js/main.js') }}"></script>
    <script src="{{ asset('assets/client/js/customer.js') }}"></script>

    @stack('scripts')
</body>

</html>
