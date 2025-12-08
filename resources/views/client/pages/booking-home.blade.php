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
                    <h3 class="filter-title">Lọc tìm kiếm</h3>
                    <p class="filter-tip">Hiển thị kết quả dựa trên danh mục của bạn</p>

                    <ul class="filter-list">
                        <li class="filter-item">
                            <a href="#" class="filter-toggle">
                                Chọn điểm lên xe <span class="fa fa-chevron-down"></span>
                            </a>

                            <ul class="child_menu" style="display: none;">
                                <li>
                                    <ul class="filter-options" id="pickupOptions">
                                        @foreach ($pickupPoints as $p)
                                            <li class="filter-option" data-id="{{ $p->id }}">{{ $p->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="filter-item">
                            <a href="#" class="filter-toggle">
                                Chọn điểm đến <span class="fa fa-chevron-down"></span>
                            </a>

                            <ul class="child_menu" style="display: none;">
                                <li>
                                    <ul class="filter-options" id="dropoffOptions">
                                        @foreach ($dropoffPoints as $d)
                                            <li class="filter-option" data-id="{{ $d->id }}">{{ $d->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="filter-item">
                            <a href="#" class="filter-toggle">
                                Giờ khởi hành <span class="fa fa-chevron-down"></span>
                            </a>

                            <ul class="child_menu" style="display: none;">
                                <li>
                                    <ul class="filter-options" id="timeOptions">
                                        <li class="filter-option" data-id="00:00-06:00">0h - 6h</li>
                                        <li class="filter-option" data-id="06:00-12:00">6h - 12h</li>
                                        <li class="filter-option" data-id="12:00-18:00">12h - 18h</li>
                                        <li class="filter-option" data-id="18:00-23:59">18h - 0h</li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="filter-item">
                            <a href="#" class="filter-toggle">
                                Hãng xe <span class="fa fa-chevron-down"></span>
                            </a>

                            <ul class="child_menu" style="display: none;">
                                <li>
                                    <ul class="filter-options" id="operatorOptions">
                                        @foreach ($operators as $op)
                                            <li class="filter-option" data-id="{{ $op->id }}">{{ $op->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </li>

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

                <div id="tripResults">
                    @foreach ($schedules as $sc)
                        @include('client.components.schedule-card', ['sc' => $sc])
                    @endforeach
                </div>
            </main>
        </div>
        <div id="pageData" data-ajax-url="{{ route('ajax.filter') }}" data-date="{{ $date }}"
            data-seats="{{ $seats }}">
        </div>
    </div>
    </div>
@endsection
