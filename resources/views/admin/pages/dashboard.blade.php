@extends('layouts.admin')

@section('title', 'Trang chủ Admin')

@section('content')
    <!-- page content -->
    <h1>dcmmmmmm</h1>
    {{-- <div class="right_col" role="main">
        <div class="row" style="display: inline-block; width: 100%">
            <div class="tile_count">
                <div class="col-md-3 col-sm-4  tile_stats_count">
                    <span class="count_top"><i class="fa fa-user"></i> Tổng số người dùng</span>
                    <div class="count">{{ $users->count() }}</div>
                </div>
                <div class="col-md-3 col-sm-4  tile_stats_count">
                    <span class="count_top"><i class="fa fa-bar-chart"></i> Tổng số sản phẩm</span>
                    <div class="count">{{ $products->count() }}</div>
                </div>
                <div class="col-md-3 col-sm-4  tile_stats_count">
                    <span class="count_top"><i class="fa fa-shopping-cart"></i> Tổng số đơn hàng</span>
                    <div class="count green">{{ $orders->count() }}</div>
                </div>
                <div class="col-md-3 col-sm-4  tile_stats_count">
                    <span class="count_top"><i class="fa fa-money"></i> Doanh thu</span>
                    <div class="count">{{ number_format($orders->sum('total'), 0, 0) }} VND</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-4  ">

                <div class="x_panel">
                    <div class="x_title">
                        <h2>Doanh thu </h2>
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
                        <canvas id="revenueBarChart" data-labels='{!! $monthlyRevenue->pluck('month')->values()->toJson() !!}'
                            data-values='{!! $monthlyRevenue->pluck('revenue')->values()->toJson() !!}'>
                        </canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 ">
                <div class="x_panel tile fixed_height_320 overflow_hidden">
                    <div class="x_title">
                        <h2>Danh mục</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="" style="width:100%">
                            <tr>
                                <th style="width:37%;">
                                    <p>Top 5</p>
                                </th>
                                <th>
                                    <div class="col-lg-7 col-md-7 col-sm-7 ">
                                        <p class="">Danh mục</p>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 ">
                                        <p class="">Sản phẩm</p>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <canvas class="canvasDoughnutCategory" height="140" width="140"
                                        data-labels='{!! $categories->pluck('name')->values()->toJson() !!}' data-counts='{!! $categories->map(fn($category) => $category->products->count())->values()->toJson() !!}'
                                        style="margin: 15px 10px 10px 0">
                                    </canvas>
                                </td>
                                <td>
                                    <table class="tile_info">
                                        @foreach ($categories as $index => $category)
                                            <tr>
                                                <td>
                                                    <p><i class="fa fa-square"
                                                            style="color: {{ ['#BDC3C7', '#9B59B6', '#E74C3C', '#26B99A', '#3498DB'][$index % 5] }}">
                                                        </i>{{ $category->name }}
                                                    </p>
                                                </td>
                                                <td>{{ $category->products->count() }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Sản phẩm bán chạy <small>Danh sách</small></h2>
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
                                    <th>Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng đã bán</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topSellingProducts as $item)
                                    <tr>
                                        <th scope="row">{{ $item->id }}</th>
                                        <td>
                                            <img src="{{ $item->image_url }}" alt="anh" style="width: 34px">
                                        </td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                                        <td>{{ $item->total_sold }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6  ">
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
                                @for ($i = 0; $i < min(3, $users->count()); $i++)
                                    <tr>
                                        <td scope="row">{{ $users[$i]->id }}</td>
                                        <td scope="row">{{ $users[$i]->name }}</td>
                                        <td scope="row">{{ $users[$i]->phone_number }}</td>
                                        <td>
                                            @if ($users[$i]->status == 'banned')
                                                <span class="custom-badge badge badge-warning">Bị chặn</span>
                                            @elseif ($users[$i]->status == 'deleted')
                                                <span class="custom-badge badge badge-danger">Đã xóa</span>
                                            @elseif ($users[$i]->status == 'pending')
                                                <span class="custom-badge badge badge-primary">Đợi kích hoạt</span>
                                            @else
                                                <span class="custom-badge badge badge-success">Đã kích hoạt</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Đơn hàng mới <small>Danh sách</small></h2>
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
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt hàng</th>
                                    <th>Xem chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < min(3, $orders->count()); $i++)
                                    <tr>
                                        <td scope="row">{{ $orders[$i]->id }}</td>
                                        <td scope="row">{{ $orders[$i]->shippingAddress->fullname }}</td>
                                        <td scope="row">{{ number_format($orders[$i]->total, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($orders[$i]->status == 'pending')
                                                <span class="custom-badge badge badge-warning">Đợi xác nhận</span>
                                            @elseif ($orders[$i]->status == 'canceled')
                                                <span class="custom-badge badge badge-danger">Đã hủy</span>
                                            @elseif ($orders[$i]->status == 'processing')
                                                <span class="custom-badge badge badge-primary">Đang giao</span>
                                            @else
                                                <span class="custom-badge badge badge-success">Hoàn thành</span>
                                            @endif
                                        </td>
                                        <td>{{ $orders[$i]->created_at->format('d-m-Y H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('admin.order-detail', ['id' => $orders[$i]->id]) }}"
                                                class="btn btn-primary" target="_blank">Chi tiết</a>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
