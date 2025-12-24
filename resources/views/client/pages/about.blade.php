@extends('layouts.client')

@section('title', 'Trang chủ')
@section('content')
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
@endsection
