@extends('layouts.client')

@section('title', 'Thông tin khách hàng')

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

                <div class="step-item active">
                    <div class="step-content">
                        <span class="step-circle">3</span>
                        <span class="step-text text-nowrap">Thông tin khách hàng</span>
                    </div>
                    <span class="step-line active-line"></span>
                </div>

                <div class="step-item">
                    <div class="step-content">
                        <span class="step-circle">4</span>
                        <span class="step-text text-nowrap">Thanh toán</span>
                    </div>
                    <span class="step-line"></span>
                </div>

                <div class="step-item last-step">
                    <div class="step-content">
                        <span class="step-circle">5</span>
                        <span class="step-text text-nowrap">Vé điện tử</span>
                    </div>
                </div>
            </div>

            <h4 class="mb-4 fw-bold text-primary">Thông tin khách hàng</h4>

            <div class="row">
                <div class="col-md-8">
                    <form id="customer-form" action="{{ route('booking.storeCustomerInfo') }}" method="POST">
                        @csrf

                        @if (auth()->check())
                            <div class="alert alert-info">
                                Thông tin của bạn sẽ tự động được sử dụng để đặt vé.
                            </div>

                            <p><strong>Họ tên:</strong> {{ auth()->user()->name }}</p>
                            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                            <p><strong>Điện thoại:</strong> {{ auth()->user()->phone ?? 'Chưa có' }}</p>

                            <input type="hidden" id="passenger_name" name="passenger_name"
                                value="{{ auth()->user()->name }}">
                            <input type="hidden" id="passenger_email" name="passenger_email"
                                value="{{ auth()->user()->email }}">
                            <input type="hidden" id="passenger_phone" name="passenger_phone"
                                value="{{ auth()->user()->phone }}">
                        @else
                            <div class="mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" id="passenger_name" name="passenger_name" class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" id="passenger_phone" name="passenger_phone" class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" id="passenger_email" name="passenger_email" class="form-control"
                                    required>
                            </div>
                        @endif

                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                        <input type="hidden" name="pickup_id" value="{{ $pickupId }}">
                        <input type="hidden" name="dropoff_id" value="{{ $dropoffId }}">
                        <input type="hidden" name="selected_seats" value="{{ implode(',', $selectedSeats) }}">
                        <input type="hidden" name="total_price" value="{{ $totalPrice }}">

                    </form>
                </div>

                <div class="col-md-4">

                    <div class="order-summary-card shadow-sm">
                        <div class="order-summary-header">Thông tin chuyến đi</div>
                        <div class="order-summary-body">

                            <h5 class="fw-bold text-primary mb-2">{{ $schedule->operator->name }}</h5>

                            <p class="text-muted small mb-3">
                                Loại xe: <strong>{{ $schedule->vehicleType->name }}</strong>
                            </p>

                            <div class="summary-item">
                                <span>Điểm đón:</span>
                                <span
                                    class="fw-bold">{{ $schedule->route->pickups->firstWhere('id', $pickupId)->location->name }}</span>
                            </div>

                            <div class="summary-item">
                                <span>Điểm trả:</span>
                                <span
                                    class="fw-bold">{{ $schedule->route->dropoffs->firstWhere('id', $dropoffId)->location->name }}</span>
                            </div>

                            <div class="summary-item">
                                <span>Ghế:</span>
                                <span class="fw-bold">{{ implode(', ', $selectedSeats) }}</span>
                            </div>

                            <div class="summary-item">
                                <span>Tổng tiền:</span>
                                <span class="fw-bold text-danger">{{ number_format($totalPrice, 0, '.', ',') }} VNĐ</span>
                            </div>

                            <button type="button" class="btn btn-primary btn-lg fw-bold btn-continue-summary mt-3"
                                id="btn-continue-payment">
                                Tiếp tục
                            </button>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
