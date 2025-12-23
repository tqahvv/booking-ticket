<header id="header">
    <div class="header-top">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 col-sm-4 col-6 header-top-left">
                    <div id="logo">
                        <a href="/"><img src="{{ asset('assets/client/img/logo.png') }}" alt=""
                                title="" /></a>
                    </div>
                </div>
                <div class="col-lg-8 col-sm-8 col-6 header-top-right d-flex align-items-center justify-content-end">
                    <ul class="nav-menu d-flex align-items-center list-unstyled mb-0">
                        <li><a href="#">Khuyến mãi</a></li>
                        <li><a href="#">Hỗ trợ</a></li>
                        <li><a href="#">Hợp tác với chúng tôi</a></li>
                        <li><a href="{{ route('booking.index') }}">Đặt chỗ của tôi</a></li>

                        @auth
                            <li class="nav-item dropdown ms-2 user-menu">
                                <a class="btn btn-outline-primary dropdown-toggle btn-auth-custom" href="#"
                                    id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fa fa-user"></i> {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="{{ route('account.edit') }}">Trang cá nhân</a>
                                    <a class="dropdown-item" href="{{ route('booking.index') }}">Đặt chỗ của tôi</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" id="logout-link" class="dropdown-item">Đăng xuất</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @else
                            <li class="ms-2 auth-button">
                                <a href="{{ route('login') }}">
                                    <button class="btn btn-outline-primary btn-auth-custom">
                                        <i class="fa fa-user"></i> Đăng nhập
                                    </button>
                                </a>
                            </li>
                            <li class="ms-2 auth-button">
                                <a href="{{ route('register') }}">
                                    <button class="btn btn-outline-primary btn-auth-custom">
                                        Đăng ký
                                    </button>
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <div class="container main-menu">
        <div class="row align-items-center justify-content-between d-flex">
            <nav id="nav-menu-container">
                <ul class="nav-menu">
                    <li><a href="/">Trang chủ</a></li>
                    <li><a href="/about">Giới thiệu</a></li>
                    <li><a href="{{ route('post.index') }}">Bài viết</a></li>
                    <li><a href="{{ route('contact.index') }}">Liên hệ</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

@if (View::hasSection('breadcrumb'))
    @include('client.partials.breadcrumb')
@else
    <section class="banner-area relative">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row fullscreen align-items-center justify-content-between">
                <div class="col-lg-6 col-md-6 banner-left text-center text-md-left">
                    <h1 class="text-white">Đặt vé xe khách giá rẻ và nhiều tiện lợi trên Travelista</h1>
                    <p class="text-white">
                        Đặt vé xe khách trực tuyến nhanh chóng, dễ dàng và an toàn với Travelista. Chúng tôi cung cấp
                        dịch vụ đặt vé xe khách đa dạng, từ các tuyến đường ngắn đến dài, với nhiều lựa chọn về hãng xe
                        và
                        giá cả hợp lý. Hãy trải nghiệm sự tiện lợi và tiết kiệm thời gian khi đặt vé xe khách qua
                        Travelista ngay hôm nay!
                    </p>
                </div>
            </div>
        </div>
    </section>
@endif
