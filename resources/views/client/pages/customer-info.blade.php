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

                <div class="step-item">
                    <div class="step-content">
                        <span class="step-circle">5</span>
                        <span class="step-text text-nowrap">Vé điện tử</span>
                    </div>
                    <span class="step-line"></span>
                </div>
            </div>

            <h4 class="mb-4 fw-bold text-primary">Thông tin khách hàng</h4>

            <div class="row">
                <div class="col-md-8">
                    <form id="customer-form" action="{{ route('booking.storeCustomerInfo') }}" method="POST">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        @if (auth()->check())
                            <div class="alert alert-info">
                                Thông tin của bạn sẽ tự động được sử dụng để đặt vé.
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" id="passenger_name" name="passenger_name" class="form-control"
                                    value="{{ old('passenger_name', auth()->user()->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" id="passenger_email" name="passenger_email" class="form-control"
                                    value="{{ old('passenger_email', auth()->user()->email) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" id="passenger_phone" name="passenger_phone" class="form-control"
                                    value="{{ old('passenger_phone', auth()->user()->phone) }}" required>
                            </div>
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
                        <input type="hidden" name="total_price" id="total_price" value="{{ $totalPrice }}">
                        <input type="hidden" name="discount_amount" id="discount_amount" value="{{ $discountAmount }}">
                        <input type="hidden" name="final_price" id="final_price" value="{{ $finalPrice }}">
                        <input type="hidden" name="promotion_id" id="promotion_id" value="{{ $promotionId ?? '' }}">

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
                                <span>Tổng giá gốc:</span>
                                <span id="totalPriceLabel"
                                    class="fw-bold text-danger">{{ number_format($totalPrice, 0, '.', ',') }} VNĐ</span>
                            </div>

                            <div id="voucherCard" class="card p-2 mt-2 d-none" style="border-left: 4px solid #28a745;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold" id="voucherName">Mã giảm giá</div>
                                        <div class="text-muted" id="voucherDesc" style="font-size: 14px;">Giảm ...</div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-danger" id="removeVoucher">X</button>
                                </div>
                            </div>

                            <div class="promo-box mt-3">

                                <label class="fw-bold mb-2">Mã giảm giá</label>

                                <h6 class="text-success fw-bold">Có thể áp dụng</h6>

                                <div class="list-group mb-3" id="validPromoList">
                                    @foreach ($promotions as $promo)
                                        @php
                                            $isValid =
                                                $promo->is_active &&
                                                $promo->valid_from <= now() &&
                                                $promo->valid_to >= now();
                                        @endphp

                                        @if ($isValid)
                                            <label
                                                class="list-group-item valid-voucher-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $promo->code }}</div>
                                                    <div class="small text-muted">{{ $promo->description }}</div>
                                                </div>

                                                <input type="radio" name="promo_select" value="{{ $promo->code }}">
                                            </label>
                                        @endif
                                    @endforeach
                                </div>

                                <button type="button" id="applyPromo" class="btn btn-success w-100 fw-bold">
                                    Áp dụng mã giảm giá
                                </button>

                                <div id="promoMessage" class="mt-2 text-danger"></div>

                                <div class="mt-4">
                                    <button class="btn btn-link text-secondary p-0" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#invalidPromoCollapse">
                                        Xem mã không thể áp dụng
                                    </button>
                                    <div class="collapse mt-2" id="invalidPromoCollapse">
                                        <div class="invalid-promos">
                                            <h6 class="text-muted fw-bold">Không thể áp dụng</h6>
                                            <div class="list-group">
                                                @foreach ($promotions as $promo)
                                                    @php
                                                        $isValid =
                                                            $promo->is_active &&
                                                            $promo->valid_from <= now() &&
                                                            $promo->valid_to >= now();
                                                    @endphp

                                                    @if (!$isValid)
                                                        <div class="list-group-item invalid-voucher-item">
                                                            <div class="fw-bold text-muted">{{ $promo->code }}</div>
                                                            <div class="small text-danger">Không còn hiệu lực</div>
                                                            <div class="text-muted small">{{ $promo->description }}</div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($discountAmount > 0)
                                <div class="summary-item">
                                    <span>Giảm giá:</span>
                                    <span id="discountLabel"
                                        class="fw-bold text-success">-{{ number_format($discountAmount, 0, '.', ',') }}
                                        VNĐ</span>
                                </div>
                            @endif

                            <div class="summary-item">
                                <span>Tổng thanh toán:</span>
                                <span id="finalPriceLabel"
                                    class="fw-bold text-danger">{{ number_format($finalPrice, 0, '.', ',') }} VNĐ</span>
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
