@extends('layouts.client')


@section('title', 'Thanh toán')


@section('content')
    <div class="container my-4">
        <div class="main-booking-card card shadow-lg p-4">
            <div class="booking-progress mb-4">
                <div class="step-item done">
                    <div class="step-content">
                        <span class="step-circle">1</span>
                        <span class="step-text text-nowrap">Điểm đón - trả</span>
                    </div>
                    <span class="step-line active-line"></span>
                </div>

                <div class="step-item done">
                    <div class="step-content">
                        <span class="step-circle">2</span>
                        <span class="step-text text-nowrap">Chọn ghế</span>
                    </div>
                    <span class="step-line active-line"></span>
                </div>

                <div class="step-item done">
                    <div class="step-content">
                        <span class="step-circle">3</span>
                        <span class="step-text text-nowrap">Thông tin khách hàng</span>
                    </div>
                    <span class="step-line active-line"></span>
                </div>

                <div class="step-item active">
                    <div class="step-content">
                        <span class="step-circle">4</span>
                        <span class="step-text text-nowrap">Thanh toán</span>
                    </div>
                    <span class="step-line active-line"></span>
                </div>

                <div class="step-item">
                    <div class="step-content">
                        <span class="step-circle">5</span>
                        <span class="step-text text-nowrap">Vé điện tử</span>
                    </div>
                    <span class="step-line"></span>
                </div>
            </div>

            <h4 class="mb-4 fw-bold text-primary">Thanh toán</h4>

            <div class="row">
                <div class="col-md-7">
                    <form action="{{ route('booking.payment.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                        <h5 class="fw-bold mb-3">Chọn phương thức thanh toán</h5>

                        <div class="d-flex flex-column gap-3">
                            @foreach ($paymentMethods as $method)
                                <label
                                    class="payment-card-2 position-relative border rounded p-3 d-flex align-items-center">
                                    <input type="radio" name="payment_method_id" value="{{ $method->id }}" required>

                                    @if ($method->image)
                                        <img src="{{ $method->image_url }}" alt="{{ $method->name }}"
                                            class="payment-logo me-3">
                                    @else
                                        <div class="payment-placeholder me-3">{{ substr($method->name, 0, 2) }}</div>
                                    @endif

                                    <div class="payment-info">
                                        <div class="fw-bold">{{ $method->name }}</div>
                                        <small class="text-muted">{{ $method->description }}</small>
                                    </div>

                                </label>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mt-4 fw-bold">
                            Xác nhận thanh toán
                        </button>
                    </form>
                </div>


                <div class="col-md-5">

                    <div class="mb-4 p-3 order-summary-card shadow-sm border rounded bg-light">
                        <div class="order-summary-header">Tóm tắt đặt vé</div>
                        <div class="order-summary-body">

                            <div class="summary-item">
                                <span>Mã đặt chỗ:</span>
                                <span class="fw-bold">{{ $booking->code }}</span>
                            </div>

                            <div class="summary-item">
                                <span>Khách hàng:</span>
                                <span class="fw-bold">{{ $booking->passengers->first()->passenger_name }}</span>
                            </div>

                            <div class="summary-item">
                                <span>Số điện thoại:</span>
                                <span class="fw-bold">{{ $booking->passengers->first()->passenger_phone }}</span>
                            </div>

                            <div class="summary-item">
                                <span>Email:</span>
                                <span class="fw-bold">{{ $booking->passengers->first()->passenger_email ?? '—' }}</span>
                            </div>

                            <hr>

                            <div class="summary-item">
                                <span>Tuyến:</span>
                                <span class="fw-bold">{{ $booking->schedule->route->origin->name }} →
                                    {{ $booking->schedule->route->destination->name }}</span>
                            </div>
                            <div class="summary-item">
                                <span>Ngày khời hành:</span>
                                <span
                                    class="fw-bold">{{ \Carbon\Carbon::parse($booking->schedule->departure_datetime)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="summary-item">
                                <span>Điểm đón:</span>
                                <span
                                    class="fw-bold">{{ $booking->passengers->first()->pickupStop->location->name }}</span>
                            </div>
                            <div class="summary-item">
                                <span>Điểm trả:</span>
                                <span
                                    class="fw-bold">{{ $booking->passengers->first()->dropoffStop->location->name }}</span>
                            </div>
                            <div class="summary-item">
                                <span>Ghế:</span>
                                <span class="fw-bold">{{ $booking->passengers->pluck('seat_number')->join(', ') }}</span>
                            </div>
                            <div class="summary-item">
                                <span>Tổng tiền:</span>
                                <span class="fw-bold">{{ number_format($booking->total_price) }} VNĐ</span>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
