@extends('layouts.client')

@section('title', 'Đặt vé xe khách')
@section('content')
    <div class="container">
        <header class="search-header">
            <div class="search-info">
                <span class="route">{{ $from }} → {{ $to }}</span>
                <span class="date">{{ $formattedDate ?? 'Không có ngày' }}
                </span>
                <span class="seat-count">{{ $seats }} chỗ ngồi</span>
            </div>
            <a href="{{ url('/') }}?from={{ urlencode($from) }}&to={{ urlencode($to) }}&date={{ $date }}&seats={{ $seats }}"
                class="change-search-btn">
                Thay đổi tìm kiếm
            </a>
        </header>

        <div class="main-content">
            <aside class="sidebar">
                <div class="sidebar-box">
                    <h3 class="filter-title">Lọc</h3>
                    <button class="reset-filter-btn">Đặt lại bộ lọc</button>
                    <p class="filter-tip">Hiển thị kết quả dựa trên danh mục của bạn</p>

                    <ul class="filter-list">
                        <li><a href="#"><i class="fa fa-chevron-down"></i> Chọn điểm lên xe</a></li>
                        <li><a href="#"><i class="fa fa-chevron-down"></i> Chọn điểm đến</a></li>
                        <li><a href="#"><i class="fa fa-chevron-down"></i> Giờ khởi hành</a></li>
                        <li><a href="#"><i class="fa fa-chevron-down"></i> Giờ đến</a></li>
                        <li><a href="#"><i class="fa fa-chevron-down"></i> Hãng Xe Buýt</a></li>
                        <li><a href="#"><i class="fa fa-chevron-down"></i> Tiện ích</a></li>
                        <li><a href="#"><i class="fa fa-chevron-down"></i> Kiểu ghế ngồi</a></li>
                        <li><a href="#"><i class="fa fa-chevron-down"></i> Chỗ ngồi</a></li>
                    </ul>
                </div>
            </aside>

            <main class="results-area">
                <div class="sort-section">
                    <button class="sort-btn active">SẮP XẾP <i class="fa fa-check-circle"></i></button>
                </div>

                @if (isset($message))
                    <p class="text-danger">{{ $message }}</p>
                @endif

                @if ($schedules->isEmpty())
                    <p class="text-danger mt-3">Không có chuyến xe nào phù hợp.</p>
                @else
                    @foreach ($schedules as $sc)
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
                                        <span
                                            class="time">{{ \Carbon\Carbon::parse($sc->departure_datetime)->format('H:i') }}</span>
                                        <span class="station">{{ $sc->route->origin->name ?? '' }}</span>
                                    </div>
                                    <div class="duration-icons">
                                        <i class="fa fa-arrow-right"></i>
                                    </div>
                                    <div class="arrival">
                                        <span
                                            class="time">{{ \Carbon\Carbon::parse($sc->arrival_datetime)->format('H:i') }}</span>
                                        <span class="station">{{ $sc->route->destination->name ?? '' }}</span>
                                    </div>
                                </div>

                                <div class="price-action">
                                    <div class="price-info">
                                        <span class="price">{{ number_format($sc->base_fare, 0, ',', '.') }} VND</span>
                                        <span class="per-person">/khách</span>
                                    </div>
                                    <span class="available-seats">
                                        Còn {{ $sc->seats_available }} vé trống
                                    </span>
                                    <button class="book-btn">Đặt Ngay</button>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button class="footer-btn featured">Đặc trưng</button>
                                <button class="footer-btn route-btn">Tuyến đường</button>
                                <button class="footer-btn">Vé</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </main>
        </div>
    </div>
@endsection
