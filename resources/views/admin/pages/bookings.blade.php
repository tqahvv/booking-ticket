@extends('layouts.admin')

@section('title', 'Quản lý đặt chỗ')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách đặt chỗ</h3>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Luôn mang đến những trải nghiệm tốt nhất</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box table-responsive">
                                        <p class="text-muted font-13 m-b-30">
                                            Trang quản lý đặt chỗ cho phép admin xem và cập nhật danh sách đặt chỗ.
                                        </p>
                                        <table id="datatable-buttons" class="table table-striped table-bordered"
                                            style="width: 100%;">
                                            <thead>
                                                <tr role="row">
                                                    <th>Mã</th>
                                                    <th>Khách hàng</th>
                                                    <th>Tuyến đường</th>
                                                    <th>Thời gian đặt vé</th>
                                                    <th>Số lượng vé</th>
                                                    <th>Giá vé</th>
                                                    <th>Trạng thái</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bookings as $b)
                                                    <tr id="booking-{{ $b->id }}">
                                                        <td><strong>{{ $b->code }}</strong></td>
                                                        <td>{{ $b->passengers->first()->passenger_name }}</td>
                                                        <td>{{ $b->schedule->route->description }}</td>
                                                        <td>{{ $b->booking_date }}</td>
                                                        <td
                                                            style="display: flex; justify-content: center; align-content: center">
                                                            {{ $b->num_passengers }}</td>
                                                        <td>{{ number_format($b->total_price, 0, ',', '.') }} VNĐ</td>

                                                        <td
                                                            style="display: flex; justify-content: center; align-content: center">
                                                            <span class="badge bg-{{ $b->status_color }}"
                                                                style="padding: .25rem .5rem; font-size: .875rem; line-height: 1.5;
                                                                ">
                                                                {{ $b->status_label }}
                                                            </span>
                                                        </td>

                                                        <td
                                                            style="display: flex; justify-content: center; align-content: center">
                                                            <a href="{{ route('admin.bookings.show', $b->id) }}"
                                                                class="btn btn-app btn-primary btn-show-booking">
                                                                <i class="fa fa-eye"></i>Xem chi tiết
                                                            </a>
                                                        </td>
                                                        <td
                                                            style="display: flex; justify-content: center; align-content: center">
                                                            <button class="btn btn-app btn-danger btn-delete-booking"
                                                                data-id="{{ $b->id }}">
                                                                <i class="fa fa-trash"></i>Xóa
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
