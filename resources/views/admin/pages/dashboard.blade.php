@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="right_col" role="main">

        @if (auth('admin')->user()->role_id == 1)
            <div class="row tile_count">

                <div class="col-md-3 tile_stats_count">
                    <span class="count_top"><i class="fa fa-users"></i> Khách hàng</span>
                    <div class="count">{{ $totalUsers }}</div>
                </div>

                <div class="col-md-3 tile_stats_count">
                    <span class="count_top"><i class="fa fa-bus"></i> Nhà xe</span>
                    <div class="count">{{ $totalBusCompany }}</div>
                </div>

                <div class="col-md-3 tile_stats_count">
                    <span class="count_top"><i class="fa fa-file-text"></i> Bài viết</span>
                    <div class="count">{{ $totalPosts }}</div>
                </div>

                <div class="col-md-3 tile_stats_count">
                    <span class="count_top"><i class="fa fa-folder"></i> Danh mục</span>
                    <div class="count">{{ $totalCategories }}</div>
                </div>

            </div>
        @endif

        @if (auth('admin')->user()->role_id == 2)
            <div class="row tile_count">

                <div class="col-md-3 tile_stats_count">
                    <span class="count_top"><i class="fa fa-road"></i> Chuyến xe</span>
                    <div class="count">{{ $totalSchedules }}</div>
                </div>

                <div class="col-md-3 tile_stats_count">
                    <span class="count_top"><i class="fa fa-ticket"></i> Vé đã bán</span>
                    <div class="count">{{ $ticketsSold }}</div>
                </div>

                <div class="col-md-3 tile_stats_count">
                    <span class="count_top"><i class="fa fa-check"></i> Vé đã sử dụng</span>
                    <div class="count green">{{ $ticketsUsed }}</div>
                </div>

                <div class="col-md-3 tile_stats_count">
                    <span class="count_top"><i class="fa fa-money"></i> Doanh thu</span>
                    <div class="count">
                        {{ number_format($revenue) }} VND
                    </div>
                </div>

            </div>
        @endif

    </div>
@endsection
