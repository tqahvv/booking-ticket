<div class="trip-card">
    <div class="trip-header">
        <div class="company-details">
            <span class="company-name">{{ $sc->operator->name ?? 'Không rõ hãng' }}</span>
            <span class="bus-type">{{ $sc->vehicleType->name ?? '' }}</span>
        </div>
    </div>

    <div class="trip-details">
        <div class="time-route">
            <div class="departure">
                <span class="time">{{ \Carbon\Carbon::parse($sc->departure_datetime)->format('H:i') }}</span>
                <span class="station">{{ $sc->route->origin->name ?? '' }}</span>
            </div>

            <div class="duration-icons">
                <i class="fa fa-arrow-right"></i>
            </div>

            <div class="arrival">
                <span class="time">{{ \Carbon\Carbon::parse($sc->arrival_datetime)->format('H:i') }}</span>
                <span class="station">{{ $sc->route->destination->name ?? '' }}</span>
            </div>
        </div>

        <div class="vertical-separator"></div>

        <div class="trip-duration-block">
            <span class="trip-duration">
                {{ $sc->duration }}
            </span>
        </div>

        <div class="vertical-separator"></div>

        <div class="amenity-list">
            <i class="fa fa-snowflake"></i>
            <i class="fa fa-wifi"></i>
            <i class="fa fa-bolt"></i>
        </div>

        <div class="price-action">
            <div class="price-info">
                <span class="price">{{ number_format($sc->base_fare, 0, ',', '.') }} VND</span>
                <span class="per-person">/khách</span>
            </div>
            <span class="available-seats">Còn {{ $sc->seats_available }} vé trống</span>
            <a href="{{ route('booking.pickup', ['schedule_id' => $sc->id, 'date' => $date, 'seats' => request('seats')]) }}"
                class="book-btn">
                Đặt Ngay
            </a>
        </div>
    </div>

    <div class="card-footer">
        <div class="footer-btn featured">Đặc trưng</div>
        <div class="footer-btn route-btn">Tuyến đường</div>
    </div>

    <div class="trip-extra">
        <div class="extra-content features-content">

            <div class="features-wrapper">

                <div class="left-column">
                    <h6 style="margin-bottom: 10px; margin-top: 20px">Đặc điểm kỹ thuật xe buýt</h6>
                    <div class="feature-box">

                        <p><strong>Chỗ ngồi:</strong>
                            {{ $sc->vehicleType->capacity_total ?? 'Không rõ' }}
                            chỗ</p>
                        <p><strong>Kiểu ghế:</strong>
                            {{ $sc->vehicleType->name ?? 'Không rõ' }}</p>
                        <p><strong>Mô tả xe:</strong>
                            {{ $sc->vehicleType->description ?? 'Không rõ' }}</p>
                    </div>
                    <h6 style="margin-bottom: 10px; margin-top: 20px">Chính sách đổi và hoàn</h6>
                    <div class="policy-box">
                        <div class="policy-item">
                            <i class="fa fa-calendar-times-o policy-icon"></i>
                            <div class="policy-text">
                                <strong>Không đổi lịch</strong>
                                <p>Không thể đổi lịch sau khi đặt chỗ.</p>
                            </div>
                        </div>

                        <div class="policy-item refundable">
                            <i class="fa fa-money policy-icon"></i>
                            <div class="policy-text">
                                <strong>Có thể hoàn trả</strong>
                                <p>Để hủy vé và yêu cầu hoàn tiền, hãy liên hệ với Xevenha.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="right-column">
                    <div class="bus-image-box">
                        <img src="{{ asset('assets/client/img/xe-khach-1.jpg') }}" class="bus-image">
                        <div class="slider-dots">
                            <span></span><span></span><span></span><span></span><span></span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="extra-content route-content">
            <div class="left-column">
                <div class="route-row1">
                    <div class="time-col">
                        <div class="dot start"></div>

                        <strong>{{ $sc->departure_datetime->format('H:i') }}</strong>
                        <div class="date">
                            {{ $sc->departure_datetime->translatedFormat('d \\t\\h\\g m') }}
                        </div>
                        <div class="route-line"></div>
                    </div>

                    <div class="info-col">
                        <strong>{{ $sc->route->origin->name }}</strong>
                        <div class="address">{{ $sc->route->origin->address }}</div>
                    </div>
                </div>
                <div class="route-row2">
                    <div class="time-col">
                        <div class="dot end"></div>

                        <strong>{{ $sc->arrival_datetime->format('H:i') }}</strong>
                        <div class="date">
                            {{ $sc->arrival_datetime->translatedFormat('d \\t\\h\\g m') }}
                        </div>
                    </div>

                    <div class="info-col">
                        <strong>{{ $sc->route->destination->name }}</strong>
                        <div class="address">{{ $sc->route->destination->address }}</div>
                    </div>
                </div>
            </div>
            <div class="right-column">
                <p>abc</p>
            </div>
        </div>
    </div>
</div>
