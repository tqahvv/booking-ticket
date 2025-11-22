@extends('layouts.client')

@section('title', 'Chọn ghế')

@section('content')

    <div class="container my-4 seat-selection-container">
        <div class="main-booking-card card shadow-lg p-4">
            <div class="booking-progress mb-4">
                <div class="step-item done">
                    <div class="step-content">
                        <span class="step-circle">1</span>
                        <span class="step-text text-nowrap">Điểm đón - trả</span>
                    </div>
                    <span class="step-line active-line"></span>
                </div>

                <div class="step-item active">
                    <div class="step-content">
                        <span class="step-circle">2</span>
                        <span class="step-text text-nowrap">Chọn ghế</span>
                    </div>
                    <span class="step-line active-line"></span>
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

                <div class="step-item last-step">
                    <div class="step-content">
                        <span class="step-circle">5</span>
                        <span class="step-text text-nowrap">Vé điện tử</span>
                    </div>
                </div>
            </div>

            <hr class="my-0 mb-4">
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="seat-map-container card border-0 p-4 shadow-sm h-100">
                        <h4 class="mb-4 fw-bold text-primary">
                            Sơ đồ xe
                        </h4>

                        <div class="seat-legend-updated mb-4 p-3 bg-light rounded d-flex align-items-center border">
                            <h5 class="fw-bold mb-0 me-4 text-nowrap">Chú thích:</h5>
                            <div class="d-flex flex-wrap gap-3">
                                <span class="seat-legend-item"><span class="seat-box available-box"></span> Chỗ trống</span>
                                <span class="seat-legend-item"><span class="seat-box selected-box"></span> Đang chọn</span>
                                <span class="seat-legend-item"><span class="seat-box booked-box"></span> Ghế đã đặt</span>
                                <span class="seat-legend-item"><span class="seat-box fixed-box"></span> Lái/Phụ</span>
                            </div>
                        </div>
                        @php
                            $busTypeName = $schedule->vehicleType->name;
                            $isSleeperBus = Str::contains($busTypeName, 'Giường nằm');
                            $rowsPerDeck = 10;
                            $maxDisplayRow = $rowsPerDeck + 1;
                            $basePrice = $basePrice ?? 0;
                        @endphp

                        <div class="bus-frame">
                            @if ($isSleeperBus)
                                @php
                                    $leftCols = 1;
                                    $rightCols = 1;
                                    $aislePosition = $leftCols + 1;
                                    $totalDisplayCols = $leftCols + 1 + $rightCols;
                                @endphp

                                <div class="d-flex flex-wrap gap-4 justify-content-center sleeper-decks-wrapper">
                                    <div class="deck-wrapper card p-3 shadow-sm border">
                                        <h5 class="deck-title mb-3 text-center fw-bold text-primary">Tầng Dưới</h5>
                                        <div class="seat-map-grid bed_map bed_40_lower"
                                            style="grid-template-columns: 1fr 0.3fr 1fr;">
                                            <div class="seat-item seat-fixed driver-seat" data-seat-name="Lái">🛞</div>
                                            <div class="seat-gap aisle"></div>
                                            <div class="seat-gap"></div>

                                            @for ($displayR = 2; $displayR <= $maxDisplayRow; $displayR++)
                                                @php
                                                    $bedRow = $displayR - 1;
                                                @endphp

                                                @for ($displayC = 1; $displayC <= $totalDisplayCols; $displayC++)
                                                    @if ($displayC == $aislePosition)
                                                        <div class="seat-gap aisle"></div>
                                                    @else
                                                        @php
                                                            $bedColDB = $displayC < $aislePosition ? 1 : 2;
                                                            $bedNumber = $bedColDB == 1 ? $bedRow : $bedRow + 10;
                                                            $targetSeatCode = "D{$bedNumber}";
                                                            $bed = $seats->first(
                                                                fn($s) => $s->deck == 1 &&
                                                                    $s->seat_code == $targetSeatCode,
                                                            );
                                                            $bedName = $bed->seat_code ?? $targetSeatCode;
                                                            $isBooked = in_array($bedName, $bookedSeats);
                                                        @endphp

                                                        @if (!$bed && $bedRow > $rowsPerDeck)
                                                            <div class="seat-gap"></div>
                                                        @elseif ($isBooked)
                                                            <div class="seat-item bed-item booked">{{ $bedName }}</div>
                                                        @else
                                                            <div class="seat-item bed-item available"
                                                                data-seat="{{ $bedName }}"
                                                                data-price="{{ $basePrice }}">
                                                                {{ $bedName }}
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endfor
                                            @endfor
                                        </div>
                                    </div>

                                    <div class="deck-wrapper card p-3 shadow-sm border">
                                        <h5 class="deck-title mb-3 text-center fw-bold text-primary">Tầng Trên</h5>
                                        <div class="seat-map-grid bed_map bed_40_upper"
                                            style="grid-template-columns: 1fr 0.3fr 1fr;">
                                            @for ($i = 1; $i <= 8; $i++)
                                                <div class="seat-gap"></div>
                                                <div></div>
                                                <div class="seat-gap"></div>
                                            @endfor
                                            @for ($displayR = 2; $displayR <= $maxDisplayRow; $displayR++)
                                                @php
                                                    $bedRow = $displayR - 1;
                                                @endphp

                                                @for ($displayC = 1; $displayC <= $totalDisplayCols; $displayC++)
                                                    @if ($displayC == $aislePosition)
                                                        <div class="seat-gap aisle"></div>
                                                    @else
                                                        @php
                                                            $bedColDB = $displayC < $aislePosition ? 1 : 2;
                                                            $bedNumber = $bedColDB == 1 ? $bedRow : $bedRow + 10;
                                                            $targetSeatCode = "U{$bedNumber}";
                                                            $bed = $seats->first(
                                                                fn($s) => $s->deck == 2 &&
                                                                    $s->seat_code == $targetSeatCode,
                                                            );

                                                            $bedName = $bed->seat_code ?? $targetSeatCode;
                                                            $isBooked = in_array($bedName, $bookedSeats);
                                                        @endphp

                                                        @if (!$bed && $bedRow > $rowsPerDeck)
                                                            <div class="seat-gap"></div>
                                                        @elseif ($isBooked)
                                                            <div class="seat-item bed-item booked">{{ $bedName }}</div>
                                                        @else
                                                            <div class="seat-item bed-item available"
                                                                data-seat="{{ $bedName }}"
                                                                data-price="{{ $basePrice }}">
                                                                {{ $bedName }}
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endfor
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            @else
                                @php
                                    $maxRow = $seats->max('row') ?: 10;
                                    $leftCols = 2;
                                    $rightCols = 3;
                                    $aislePosition = $leftCols + 1;
                                    $totalDisplayCols = $leftCols + 1 + $rightCols;
                                @endphp

                                <div class="seat-map-grid seat_40"
                                    style="grid-template-columns: repeat(2, 1fr) 0.3fr repeat(3, 1fr);">
                                    <div class="seat-item seat-fixed driver-seat" data-seat-name="R1C1">🛞</div>
                                    <div class="seat-gap" style="grid-column: 2 / span {{ $totalDisplayCols - 1 }};"></div>

                                    @for ($r = 2; $r <= $maxRow; $r++)
                                        @for ($displayC = 1; $displayC <= $totalDisplayCols; $displayC++)
                                            @if ($displayC == $aislePosition)
                                                <div class="seat-gap aisle"></div>
                                            @else
                                                @php
                                                    $seatCol = $displayC < $aislePosition ? $displayC : $displayC - 1;
                                                    $isGapR7C5 =
                                                        $schedule->vehicleType->seat_count == 29 &&
                                                        $r == 7 &&
                                                        $seatCol == 5;
                                                    $seat = $seats->first(
                                                        fn($s) => $s->row == $r && $s->column == $seatCol,
                                                    );
                                                    $seatName = $seat->seat_code ?? "R{$r}C{$seatCol}";
                                                    $isBooked = in_array($seatName, $bookedSeats);
                                                @endphp

                                                @if ($isGapR7C5)
                                                    <div class="seat-gap"></div>
                                                @elseif (!$seat)
                                                    <div class="seat-gap"></div>
                                                @elseif ($isBooked)
                                                    <div class="seat-item booked">{{ $seatName }}</div>
                                                @else
                                                    <div class="seat-item seat-only available"
                                                        data-seat="{{ $seatName }}" data-price="{{ $basePrice }}">
                                                        {{ $seatName }}
                                                    </div>
                                                @endif
                                            @endif
                                        @endfor
                                    @endfor
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="order-summary-card shadow-sm">
                        <div class="order-summary-header">Thông tin chuyến đi</div>
                        <div class="order-summary-body">
                            <h5 class="fw-bold text-primary mb-2">{{ $schedule->operator->name }}</h5>
                            <p class="text-muted small mb-3">Loại xe: <span
                                    id="summary-bus-type">{{ $schedule->vehicleType->name }}</span></p>

                            <div class="summary-item">
                                <span class="fw-medium">Điểm đón:</span>
                                <span class="fw-bold text-end">
                                    {{ $schedule->route->pickups->firstWhere('id', $pickup_id)->location->name }}
                                </span>
                            </div>
                            <div class="summary-item">
                                <span class="fw-medium">Điểm trả:</span>
                                <span class="fw-bold text-end">
                                    {{ $schedule->route->dropoffs->firstWhere('id', $dropoff_id)->location->name }}
                                </span>
                            </div>
                            <div class="summary-item">
                                <span class="fw-medium">Ghế đã chọn:</span>
                                <span id="summary-selected-seats" class="fw-bold text-danger text-end">Chưa chọn</span>
                            </div>
                            <div class="summary-item">
                                <span class="fw-medium">Giá vé cơ bản:</span>
                                <span class="fw-bold text-success">{{ number_format($basePrice, 0, ',', '.') }}VNĐ</span>
                            </div>
                            <div class="summary-item border-bottom-0 pt-3">
                                <span class="fw-bold total-label">Tổng tiền:</span>
                                <span id="summary-total-fare" class="fw-bolder text-danger total-amount"
                                    data-base-price="{{ $basePrice }}"
                                    data-max-seats="{{ $maxSeats }}">{{ number_format($basePrice, 0, ',', '.') }}
                                    VNĐ</span>
                            </div>
                            <button type="button" class="btn btn-primary btn-lg fw-bold btn-continue-summary mt-3"
                                disabled id="btn-continue">
                                Tiếp tục
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
