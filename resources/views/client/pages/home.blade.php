@extends('layouts.client')

@section('title', 'Trang chủ')
@section('content')
    <div class="container">
        <div class="hotel-search-form">
            <form class="row g-3" id="searchForm" method="GET" action="{{ route('search.results') }}">
                <div class="col-md-12">
                    <h4>Xe khách và xe đưa đón</h4>
                </div>

                <div class="col-md-12 d-flex align-items-end">
                    <div class="w-50 pe-2 position-relative search-input-wrap">
                        <label class="form-label fw-bold">Từ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                            <input autocomplete="off" type="text" value="{{ old('from', $from ?? '') }}"
                                class="form-control autocomplete-input" id="fromCity" name="from"
                                placeholder="Nhập thành phố hoặc điểm đi" required />
                        </div>
                        <div id="fromSuggestions" class="suggestion-box"></div>
                    </div>

                    <div class="d-flex align-items-center justify-content-center" style="margin: 0 5px 6px 5px;">
                        <button type="button" id="swapBtn" class="btn btn-outline-primary">
                            <i class="fa fa-exchange"></i>
                        </button>
                    </div>

                    <div class="w-50 ps-2 position-relative search-input-wrap">
                        <label class="form-label fw-bold">Đến</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                            <input autocomplete="off" type="text" value="{{ old('to', $to ?? '') }}"
                                class="form-control autocomplete-input" id="toCity" name="to"
                                placeholder="Nhập thành phố hoặc điểm đến" required />
                        </div>
                        <div id="toSuggestions" class="suggestion-box"></div>
                    </div>
                </div>

                <div class="col-md-3 theme-fill">
                    <label class="form-label fw-bold">Ngày khởi hành</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        <input type="date" class="form-control" value="{{ old('date', $date ?? '') }}" name="date"
                            required>
                    </div>
                </div>

                <div class="col-md-3 theme-fill">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="roundTrip">
                        <label class="form-check-label fw-bold" for="roundTrip">
                            Khứ hồi
                        </label>
                    </div>

                    <div id="returnDateGroup" style="display:none;">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            <input type="date" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="col-md-3 theme-fill">
                    <label class="form-label fw-bold">Số ghế</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input type="number" class="form-control" value="{{ old('seats', $seats ?? 1) }}" name="seats"
                            value="1" min="1" required>
                    </div>
                </div>

                <div class="col-md-3 d-flex align-items-end" style="padding: 10px">
                    <button type="submit" class="btn btn-search w-100">
                        <i class="fa fa-search"></i> Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <section class="popular-destination-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Các điểm đến phổ biến</h1>
                        <p>Chúng tôi cung cấp các tuyến xe phổ biến cho mỗi chuyến đi của bạn.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($populars as $popular)
                    <div class="col-lg-4">
                        <div class="single-destination relative">
                            <div class="thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="{{ $popular->url }}" alt="{{ $popular->alt_text }}">
                            </div>
                            <div class="desc">
                                <a href="">Vé Xe Đi {{ $popular->alt_text }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
    </section>

    <section class="price-area section-gap">
        <div class="container">
            <hr class="section-divider">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Giá Vé Tuyến Phổ Biến</h1>
                        <p>Cập nhật giá vé xe khách tốt nhất cho các tuyến đường phổ biến.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="single-price price-box-custom">
                        <div class="price-header-custom" style="background-image: url('URL_ANH_HA_NOI.jpg');">
                            <p class="ticket-label">Vé xe khách từ</p>
                            <h4 class="ticket-route">Hà Nội</h4>
                            <span class="ticket-price-label">Giá từ:</span>
                        </div>

                        <ul class="price-list">
                            <li class="d-flex justify-content-between align-items-center">
                                <a href="#" class="route-link">Sài Gòn</a>
                                <span class="price-value">1.058.000 <small>VND</small></span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <a href="#" class="route-link">Hải Phòng</a>
                                <span class="price-value">115.000 <small>VND</small></span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <a href="#" class="route-link">Huế</a>
                                <span class="price-value">402.500 <small>VND</small></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="single-price price-box-custom">
                        <div class="price-header-custom" style="background-image: url('URL_ANH_SAI_GON.jpg');">
                            <p class="ticket-label">Vé xe khách từ</p>
                            <h4 class="ticket-route">Sài Gòn</h4>
                            <span class="ticket-price-label">Giá từ:</span>
                        </div>

                        <ul class="price-list">
                            <li class="d-flex justify-content-between align-items-center">
                                <a href="#" class="route-link">Đà Nẵng</a>
                                <span class="price-value">517.500 <small>VND</small></span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <a href="#" class="route-link">Hà Nội</a>
                                <span class="price-value">1.058.000 <small>VND</small></span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <a href="#" class="route-link">Đà Lạt</a>
                                <span class="price-value">207.000 <small>VND</small></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="single-price price-box-custom">
                        <div class="price-header-custom" style="background-image: url('URL_ANH_DA_NANG.jpg');">
                            <p class="ticket-label">Vé xe khách từ</p>
                            <h4 class="ticket-route">Đà Nẵng</h4>
                            <span class="ticket-price-label">Giá từ:</span>
                        </div>

                        <ul class="price-list">
                            <li class="d-flex justify-content-between align-items-center">
                                <a href="#" class="route-link">Sài Gòn</a>
                                <span class="price-value">517.500 <small>VND</small></span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <a href="#" class="route-link">Quảng Ngãi</a>
                                <span class="price-value">287.500 <small>VND</small></span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <a href="#" class="route-link">Gia Lai</a>
                                <span class="price-value">284.400 <small>VND</small></span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="home-about-area" style="color: #222;">
        <hr class="section-divider">
        <div class="row d-flex justify-content-center">
            <div class="menu-content pb-70 col-lg-8">
                <div class="title text-center">
                    <h1 class="mb-10">Lý do nên lựa chọn đặt vé trên Travelista</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center" style="margin-bottom: 35px;">
                <div class="col-lg-3 col-md-4 col-sm-6 text-right">
                    <img class="img-fluid" src="{{ asset('assets/client/img/about-img.jpg') }}" alt="Minh họa Tiện lợi"
                        style="max-width: 250px; padding-bottom: 50px;">
                </div>

                <div class="col-lg-5 col-md-8 col-sm-12 home-about-left">
                    <h1 style="font-size: 24px;">Tiện lợi</h1>
                    <p style="max-width: 550px;">Mua vé xe khách mọi lúc, mọi nơi với Traveloka mà không cần
                        tốn nhiều thời gian đến nhà ga hoặc văn phòng đại lý. Giờ đây bạn có thể mua vé thoải mái như ở nhà.
                    </p>
                </div>
            </div>

            <div class="row align-items-center justify-content-center" style="margin-bottom: 35px;">
                <div class="col-lg-5 col-md-8 col-sm-12 home-about-left right-side-text-block">
                    <h1 style="font-size: 24px;">Tiện lợi</h1>
                    <p style="max-width: 550px;">Mua vé xe khách mọi lúc, mọi nơi với Traveloka mà không cần
                        tốn nhiều thời gian đến nhà ga hoặc văn phòng đại lý. Giờ đây bạn có thể mua vé thoải mái như ở nhà.
                    </p>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 text-left">
                    <img class="img-fluid" src="{{ asset('assets/client/img/about-img.jpg') }}" alt="Minh họa Tiện lợi"
                        style="max-width: 250px; padding-bottom: 50px;">
                </div>
            </div>

            <div class="row align-items-center justify-content-center" style="margin-bottom: 35px;">
                <div class="col-lg-3 col-md-4 col-sm-6 text-right">
                    <img class="img-fluid" src="{{ asset('assets/client/img/about-img.jpg') }}" alt="Minh họa Tiện lợi"
                        style="max-width: 250px; padding-bottom: 50px;">
                </div>

                <div class="col-lg-5 col-md-8 col-sm-12 home-about-left">
                    <h1 style="font-size: 24px;">Tiện lợi</h1>
                    <p style="max-width: 550px;">Mua vé xe khách mọi lúc, mọi nơi với Traveloka mà không cần
                        tốn nhiều thời gian đến nhà ga hoặc văn phòng đại lý. Giờ đây bạn có thể mua vé thoải mái như ở nhà.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="recent-blog-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-60 col-lg-9">
                    <div class="title text-center">
                        <h1 class="mb-10">Các bài viết mới nhất</h1>
                        <p>Chúng tôi luôn cập nhật những bài viết mới nhất cho mọi người</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="active-recent-blog-carusel">
                    @php
                        $defaultImageUrl = asset('storage/uploads/images/default.jpg');
                    @endphp
                    @foreach ($posts as $post)
                        <div class="single-recent-blog-post item">
                            <div class="thumb">
                                <a href="{{ route('post.detail', $post->slug) }}">
                                    <img class="img-fluid" src="{{ $post->image_url }}" alt="{{ $post->title }}"
                                        onerror="this.onerror=null; this.src='{{ $defaultImageUrl }}';">
                                </a>
                            </div>
                            <div class="details">
                                <a href="{{ route('post.detail', $post->slug) }}">
                                    <h4 class="title">{{ Str::limit(strip_tags($post->title), 70, '...') }}</h4>
                                </a>
                                <p>{{ Str::limit(strip_tags($post->excerpt), 100, '...') }}</p>
                                <h6 class="date">
                                    {{ $post->published_at ? $post->published_at->format('d-m-Y') : 'N/A' }}</h6>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <a href="{{ route('post.index') }}" class="btn btn-primary mt-3 view-all">
                        Đọc thêm các bài viết >>>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
