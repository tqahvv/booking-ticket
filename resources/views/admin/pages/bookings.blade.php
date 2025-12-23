@extends('layouts.admin')

@section('title', 'Quản lý đặt chỗ')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h2>Danh sách các lượt đặt vé</h2>
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
                                                    <th class="text-center align-middle" style="width: 5%">Mã đặt vé</th>
                                                    <th class="text-center align-middle" style="width: 8%">Khách hàng</th>
                                                    <th class="text-center align-middle" style="width: 10%">Tuyến đường</th>
                                                    <th class="text-center align-middle" style="width: 10%">Thời gian đặt vé
                                                    </th>
                                                    <th class="text-center align-middle" style="width: 5%">Số lượng vé</th>
                                                    <th class="text-center align-middle" style="width: 5%">Giá vé</th>
                                                    <th class="text-center align-middle" style="width: 10%">Trạng thái</th>
                                                    <th class="text-center align-middle" style="width: 10%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bookings as $date => $dailyBookings)
                                            <tbody>
                                                <tr>
                                                    <td colspan="8" class="bg-info text-white text-center fw-bold">
                                                        📅 Ngày đặt vé:
                                                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                                    </td>
                                                </tr>
                                                @foreach ($dailyBookings as $b)
                                                    <tr id="booking-{{ $b->id }}">
                                                        <td class="text-center align-middle">
                                                            <strong>{{ $b->code }}</strong>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            {{ $b->passengers->first()->passenger_name }}</td>
                                                        <td class="text-center align-middle">
                                                            {{ $b->schedule->route->description }}</td>
                                                        <td class="text-center align-middle">{{ $b->booking_date }}</td>
                                                        <td class="text-center align-middle">
                                                            {{ $b->num_passengers }}</td>
                                                        <td class="text-center align-middle">
                                                            {{ number_format($b->final_price, 0, ',', '.') }} VNĐ</td>

                                                        <td class="text-center align-middle">
                                                            <span class="badge bg-{{ $b->status_color }} btn-sm"
                                                                style="padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; color: #fff">
                                                                {{ $b->status_label }}
                                                            </span>
                                                        </td>

                                                        <td class="text-center">
                                                            <a href="{{ route('admin.bookings.show', $b->id) }}"
                                                                class="btn btn-primary btn-sm btn-show-booking">
                                                                <i class="fa fa-eye"></i>Xem chi tiết
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
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
