@extends('layouts.client')

@section('title', 'Liên hệ')
@section('breadcrumb', 'Liên hệ')

@section('content')
    <section class="contact-page-area section-gap" style="padding: 100px 0">
        <div class="container">
            <div class="row">
                <div class="map-wrap" style="width:100%; height: 445px;" id="map">
                    <div class="map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d110674.91908850655!2d105.6144106345693!3d18.736751973192835!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3139ce640b5a1dad%3A0xf8266890856bbaa1!2zVHAuIFZpbmgsIE5naOG7hyBBbiwgVmnhu4d0IE5hbQ!5e1!3m2!1svi!2s!4v1755761948732!5m2!1svi!2s"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <div class="col-lg-4 d-flex flex-column address-wrap">
                    <div class="single-contact-address d-flex flex-row">
                        <div class="icon">
                            <span class="lnr lnr-home"></span>
                        </div>
                        <div class="contact-details">
                            <h5>Vinh, Nghệ An</h5>
                            <p>
                                123 Đại lộ Lê-Nin
                            </p>
                        </div>
                    </div>
                    <div class="single-contact-address d-flex flex-row">
                        <div class="icon">
                            <span class="lnr lnr-phone-handset"></span>
                        </div>
                        <div class="contact-details">
                            <h5>0987672267</h5>
                            <p>Thứ 2 đến thứ 7 vào giờ hành chính</p>
                        </div>
                    </div>
                    <div class="single-contact-address d-flex flex-row">
                        <div class="icon">
                            <span class="lnr lnr-envelope"></span>
                        </div>
                        <div class="contact-details">
                            <h5>admin@booking.com</h5>
                            <p>Gửi cho chúng tôi câu hỏi của bạn bất cứ lúc nào!</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <form class="form-area contact-form text-right" id="contact-form" action="{{ route('contact') }}"
                        method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <input class="common-input mb-20 form-control" type="text" name="name"
                                    placeholder="Họ và tên" required>

                                <input class="common-input mb-20 form-control" type="text" name="phone"
                                    placeholder="Số điện thoại" required>

                                <input class="common-input mb-20 form-control" type="email" name="email"
                                    placeholder="Địa chỉ email" required>
                            </div>
                            <div class="col-lg-6 form-group">
                                <textarea class="common-textarea form-control" placeholder="Nhập tin nhắn" name="message" required></textarea>
                            </div>
                            <div class="col-lg-12">
                                <div class="alert-msg" style="text-align: left;"></div>
                                <button class="genric-btn primary" style="float: right;">Gửi tin nhắn</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @push('scripts')
        @if (session('success'))
            <script>
                toastr.success("{{ session('success') }}", "Thành công");
            </script>
        @endif

        @if ($errors->any())
            <script>
                toastr.error("{!! implode('<br>', $errors->all()) !!}", "Lỗi");
            </script>
        @endif
    @endpush

@endsection
