@extends('layouts.client')

@section('title', 'Chọn điểm đón')

@section('content')
    <div class="container my-4 pickup-container">
        <div class="main-booking-card card shadow-lg p-4">
            <div class="booking-progress mb-4">
                <div class="step-item active">
                    <div class="step-content">
                        <span class="step-circle">1</span>
                        <span class="step-text text-nowrap">Điểm đón - trả</span>
                    </div>
                    <span class="step-line active-line"></span>
                </div>

                <div class="step-item">
                    <div class="step-content">
                        <span class="step-circle">2</span>
                        <span class="step-text text-nowrap">Chọn ghế</span>
                    </div>
                    <span class="step-line"></span>
                </div>

                <div class="step-item">
                    <div class="step-content">
                        <span class="step-circle">3</span>
                        <span class="step-text text-nowrap">Thông tin khách hàng</span>
                    </div>
                    <span class="step-line"></span>
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

            <hr class="my-0 mb-4">

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="schedule-info-card-updated row py-3 align-items-center mb-4 mx-0">
                        <div class="col-md-5 col-sm-12 d-flex align-items-center info-left-section">
                            <div class="bus-image-container me-4">
                                <img src="{{ asset('assets/client/img/xe-khach-1.jpg') }}" alt="Hình ảnh xe khách">
                            </div>
                            <div class="flex-grow-1 pt-1">
                                <h5 class="mb-0 fw-bold text-primary">{{ $schedule->operator->name }}</h5>
                                <p class="text-muted small mb-0">{{ $schedule->vehicleType->name }}</p>
                            </div>
                        </div>

                        <div class="col-auto d-none d-md-flex p-0">
                            <div class="vertical-divider"></div>
                        </div>

                        <div class="col route-details-updated ps-md-4">
                            <div class="d-flex justify-content-start align-items-center flex-wrap flex-md-nowrap">

                                <div class="text-start me-4">
                                    <p class="mb-1 fw-bold time-dept">{{ $departure->format('H:i') }}</p>
                                    <p class="mb-0 location-name-dept text-muted">
                                        {{ $schedule->route->origin->name }}</p>
                                </div>

                                <div class="mx-3 travel-icons d-flex flex-column align-items-center">
                                    <i class="bi bi-arrow-right-circle-fill text-primary" style="font-size: 1.8rem"></i>
                                    <p class="mb-0 text-muted small duration">{{ $durationText }}</p>
                                </div>

                                <div class="text-start ms-4">
                                    <p class="mb-1 fw-bold time-arrival"> {{ $arrival->format('H:i') }} </p>
                                    <p class="mb-0 location-name-arrival text-muted">
                                        {{ $schedule->route->destination->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pickup-dropoff-container row g-4">
                        <div class="col-md-6">
                            <div class="location-card-updated border rounded p-4 h-100 bg-light shadow-sm">
                                <div class="location-header d-flex align-items-center mb-4">
                                    <h5 class="fw-bold mb-0 text-primary">
                                        <span class="me-2"><i class="bi bi-pin-map-fill"></i></span> Điểm đón
                                    </h5>
                                </div>

                                <div class="location-list pickup-list">
                                    @foreach ($schedule->route->pickups as $index => $p)
                                        <label
                                            class="location-item-updated d-flex align-items-start p-3 mb-2 rounded bg-white shadow-xs"
                                            data-location-name="{{ $p->location->name }}">
                                            <input type="radio" name="pickup_id" value="{{ $p->id }}"
                                                class="form-check-input mt-1 custom-radio-fix"
                                                {{ $index == 0 ? 'checked' : '' }}>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex justify-content-between align-items-center mb-0">
                                                    <span
                                                        class="fw-bold location-name-radio">{{ $p->location->name }}</span>
                                                    @if ($p->time_offset)
                                                        @php
                                                            $pickupTime = $departure
                                                                ->copy()
                                                                ->addMinutes($p->time_offset);
                                                        @endphp
                                                        <span
                                                            class="fw-bold text-success">{{ $pickupTime->format('H:i') }}</span>
                                                    @endif
                                                </div>
                                                <p class="text-muted small mb-0 location-address-radio">
                                                    {{ $p->location->address }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="location-card-updated border rounded p-4 h-100 bg-light shadow-sm">
                                <div class="location-header d-flex align-items-center mb-4">
                                    <h5 class="fw-bold mb-0 text-primary">
                                        <span class="me-2"><i class="bi bi-pin-map-fill"></i></span> Điểm trả
                                    </h5>
                                </div>

                                <div class="location-list dropoff-list">
                                    @foreach ($schedule->route->dropoffs as $index => $p)
                                        <label
                                            class="location-item-updated d-flex align-items-start p-3 mb-2 rounded bg-white shadow-xs"
                                            data-location-name="{{ $p->location->name }}">
                                            <input type="radio" name="dropoff_id" value="{{ $p->id }}"
                                                class="form-check-input mt-1 custom-radio-fix"
                                                {{ $index == 0 ? 'checked' : '' }}>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex justify-content-between align-items-center mb-0">
                                                    <span
                                                        class="fw-bold location-name-radio">{{ $p->location->name }}</span>
                                                    @if ($p->time_offset)
                                                        @php
                                                            $dropoffTimeFromDept = $departure
                                                                ->copy()
                                                                ->addMinutes($p->time_offset);
                                                        @endphp
                                                        <span
                                                            class="fw-bold text-success">{{ $dropoffTimeFromDept->format('H:i') }}</span>
                                                    @endif
                                                </div>
                                                <p class="text-muted small mb-0 location-address-radio">
                                                    {{ $p->location->address }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">
                    <div class="order-summary-card shadow-sm">
                        <div class="order-summary-header">Thông tin chuyến đi</div>
                        <div class="order-summary-body">

                            <h5 class="fw-bold text-primary mb-2">{{ $schedule->operator->name }}</h5>
                            <p class="text-muted small mb-3">Loại xe: {{ $schedule->vehicleType->name }}</p>

                            <div class="summary-item">
                                <span class="fw-medium">Điểm đón:</span>
                                <span id="summary-pickup-name" class="fw-bold text-end"></span>
                            </div>

                            <div class="summary-item">
                                <span class="fw-medium">Điểm trả:</span>
                                <span id="summary-dropoff-name" class="fw-bold text-end"></span>
                            </div>

                            <div class="summary-item">
                                <span class="fw-medium">Giá vé cơ bản:</span>
                                <span class="fw-bold text-success">{{ number_format($schedule->base_fare) }} VNĐ</span>
                            </div>

                            <div class="summary-item">
                                <span class="fw-medium">Chỗ trống:</span>
                                <span class="fw-bold text-danger">{{ $seatsAvailable }}</span>
                            </div>

                            <button type="button" id="btn-choose-seat" data-schedule="{{ $schedule->id }}"
                                data-seats="{{ $seatsNeeded }}"
                                class="btn btn-primary btn-lg fw-bold btn-continue-summary mt-3">
                                Tiếp tục
                            </button>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
