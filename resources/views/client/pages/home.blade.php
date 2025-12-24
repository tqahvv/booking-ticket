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

    <section class="home-about-area" style="color: #222;">
        <hr class="section-divider">
        <div class="row d-flex justify-content-center">
            <div class="menu-content pb-70 col-lg-8">
                <div class="title text-center">
                    <h1 class="mb-10">Lý do nên lựa chọn đặt vé trên Xevenha</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center" style="margin-bottom: 35px;">
                <div class="col-lg-3 col-md-4 col-sm-6 text-right">
                    <img class="img-fluid about-image-wrapper" src="{{ asset('assets/client/img/about/q.png') }}"
                        alt="Minh họa Tiện lợi" style="max-width: 250px; padding-bottom: 50px;">
                </div>

                <div class="col-lg-5 col-md-8 col-sm-12 home-about-left">
                    <h1 style="font-size: 24px;">Tiện lợi</h1>
                    <p style="max-width: 550px;">Với Xevenha, bạn có thể đặt vé xe khách mọi lúc, mọi nơi chỉ với vài
                        thao tác đơn giản.
                        Không cần đến bến xe hay phòng vé, mọi thông tin đều được cung cấp đầy đủ và rõ ràng ngay trên hệ
                        thống.
                    </p>
                </div>
            </div>

            <div class="row align-items-center justify-content-center" style="margin-bottom: 35px;">
                <div class="col-lg-5 col-md-8 col-sm-12 home-about-left right-side-text-block">
                    <h1 style="font-size: 24px;">Nhanh chóng</h1>
                    <p style="max-width: 550px;">
                        Quy trình đặt vé được tối ưu giúp bạn tìm kiếm chuyến xe, chọn ghế và hoàn tất đặt vé chỉ trong vài
                        phút.
                        Xevenha giúp tiết kiệm thời gian và mang lại trải nghiệm đặt vé nhanh gọn, hiệu quả.
                    </p>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 text-left">
                    <img class="img-fluid about-image-wrapper" src="{{ asset('assets/client/img/about/q2.png') }}"
                        alt="Minh họa Tiện lợi" style="max-width: 250px; padding-bottom: 50px;">
                </div>
            </div>

            <div class="row align-items-center justify-content-center" style="margin-bottom: 35px;">
                <div class="col-lg-3 col-md-4 col-sm-6 text-right">
                    <img class="img-fluid about-image-wrapper" src="{{ asset('assets/client/img/about/q3.png') }}"
                        alt="Minh họa Tiện lợi" style="max-width: 250px; padding-bottom: 50px;">
                </div>

                <div class="col-lg-5 col-md-8 col-sm-12 home-about-left">
                    <h1 style="font-size: 24px;">Dễ dàng</h1>
                    <p style="max-width: 550px;">Giao diện thân thiện, dễ sử dụng phù hợp với mọi đối tượng người dùng.
                        Ngay cả những người ít tiếp xúc với công nghệ vẫn có thể dễ dàng tìm kiếm và đặt vé trên Xevenha.
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
