@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="right_col" role="main">
        <div class="row" style="display: inline-block; width: 100%">
            @if (auth('admin')->user()->role_id == 1)
                <div class="row tile_count dashboard-cards">

                    <div class="col-md-3 col-sm-6">
                        <div class="dashboard-card bg-primary">
                            <div class="card-icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="card-content">
                                <span class="card-title">Khách hàng</span>
                                <h2>{{ $totalUsers }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="dashboard-card bg-info">
                            <div class="card-icon">
                                <i class="fa fa-bus"></i>
                            </div>
                            <div class="card-content">
                                <span class="card-title">Nhà xe</span>
                                <h2>{{ $totalBusCompany }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="dashboard-card bg-success">
                            <div class="card-icon">
                                <i class="fa fa-file-text"></i>
                            </div>
                            <div class="card-content">
                                <span class="card-title">Bài viết</span>
                                <h2>{{ $totalPosts }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="dashboard-card bg-warning">
                            <div class="card-icon">
                                <i class="fa fa-folder"></i>
                            </div>
                            <div class="card-content">
                                <span class="card-title">Danh mục</span>
                                <h2>{{ $totalCategories }}</h2>
                            </div>
                        </div>
                    </div>

                </div>
            @endif


            @if (auth('admin')->user()->role_id == 2)
                <div class="row tile_count dashboard-cards">

                    <div class="col-md-3 col-sm-6">
                        <div class="dashboard-card bg-primary">
                            <div class="card-icon">
                                <i class="fa fa-road"></i>
                            </div>
                            <div class="card-content">
                                <span class="card-title">Chuyến xe</span>
                                <h2>{{ $totalSchedules }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="dashboard-card bg-info">
                            <div class="card-icon">
                                <i class="fa fa-ticket"></i>
                            </div>
                            <div class="card-content">
                                <span class="card-title">Vé đã bán</span>
                                <h2>{{ $ticketsSold }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="dashboard-card bg-success">
                            <div class="card-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="card-content">
                                <span class="card-title">Vé đã sử dụng</span>
                                <h2>{{ $ticketsUsed }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="dashboard-card bg-warning">
                            <div class="card-icon">
                                <i class="fa fa-money"></i>
                            </div>
                            <div class="card-content">
                                <span class="card-title">Doanh thu</span>
                                <h2>{{ number_format($revenue) }} VNĐ</h2>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

        </div>
        <div class="row">
            @if (auth('admin')->user()->role_id == 1)
                <div class="col-md-6">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Người dùng mới <small>Danh sách</small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Khách hàng</th>
                                        <th>Số điện thoại</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($latestUsers as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>
                                                @if ($user->status == 'banned')
                                                    <span class="badge badge-warning"
                                                        style="padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; color: #fff">Bị
                                                        chặn</span>
                                                @elseif ($user->status == 'deleted')
                                                    <span class="badge badge-danger"
                                                        style="padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; color: #fff">Đã
                                                        xóa</span>
                                                @elseif ($user->status == 'pending')
                                                    <span class="badge badge-primary"
                                                        style="padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; color: #fff">Đợi
                                                        kích hoạt</span>
                                                @else
                                                    <span class="badge badge-success"
                                                        style="padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; color: #fff">Đã
                                                        kích hoạt</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Chưa có người dùng</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Nhà xe mới <small>Danh sách</small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Hãng xe</th>
                                        <th>Mô tả</th>
                                        <th>Liên hệ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($latestBusCompanies as $bus)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $bus->name }}</td>
                                            <td>{{ $bus->description }}</td>
                                            <td>{{ $bus->contact_info }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Chưa có nhà xe</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if (auth('admin')->user()->role_id == 2)
                <div class="col-md-6">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Doanh thu theo ngày (7 ngày gần nhất)</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <canvas id="dailyRevenueChart" data-labels='@json($dailyRevenue->pluck('day'))'
                                data-values='@json($dailyRevenue->pluck('revenue'))'></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Doanh thu theo tháng</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <canvas id="monthlyRevenueChart" data-labels='@json($monthlyRevenue->pluck('month'))'
                                data-values='@json($monthlyRevenue->pluck('revenue'))'></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Đặt chỗ hôm nay</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Mã đặt chỗ</th>
                                        <th>Tên hành khách</th>
                                        <th>Số điện thoại</th>
                                        <th>Tuyến</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($todayBookings as $booking)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $booking->code }}</td>
                                            <td>{{ optional($booking->passengers->first())->passenger_name }}</td>
                                            <td>{{ optional($booking->passengers->first())->passenger_phone }}</td>
                                            <td>{{ $booking->schedule->route->description ?? '' }}</td>
                                            <td>{{ ucfirst($booking->status_label) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Chưa có đặt chỗ hôm nay</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
