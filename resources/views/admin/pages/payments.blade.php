@extends('layouts.admin')

@section('title', 'Quản lý thanh toán')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách thanh toán</h3>
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
                                            Trang quản lý thanh toán cho phép nhà xe xem và cập nhật danh sách vé xe.
                                        </p>
                                        <table id="datatable-buttons" class="table table-striped table-bordered"
                                            style="width: 100%;">
                                            <thead>
                                                <tr role="row">
                                                    <th class="text-center align-middle" style="width: 5%">Mã giao dịch</th>
                                                    <th class="text-center align-middle" style="width: 10%">Mã đặt vé</th>
                                                    <th class="text-center align-middle" style="width: 10%">Tuyến</th>
                                                    <th class="text-center align-middle" style="width: 10%">Phương thức
                                                        thanh toán</th>
                                                    <th class="text-center align-middle" style="width: 10%">Số tiền</th>
                                                    <th class="text-center align-middle" style="width: 10%">Trạng thái</th>
                                                    <th class="text-center align-middle" style="width: 10%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($payments as $date => $dailyPayments)
                                            <tbody>
                                                <tr>
                                                    <td colspan="7" class="bg-info text-white text-center fw-bold">
                                                        🚍 Ngày khởi hành:
                                                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                                    </td>
                                                </tr>
                                                @foreach ($dailyPayments as $payment)
                                                    <tr id="payment-row-{{ $payment->id }}">
                                                        <td class="text-center align-middle">
                                                            @if ($payment->transaction_code)
                                                                {{ $payment->transaction_code }}
                                                            @else
                                                                <span class="badge badge-secondary">COD</span>
                                                            @endif
                                                        </td>

                                                        <td class="text-center align-middle">
                                                            {{ $payment->booking->code }}</td>

                                                        <td class="text-center align-middle">
                                                            {{ $payment->booking->schedule->route->description }}</td>

                                                        <td class="text-center align-middle">
                                                            {{ $payment->paymentMethod->name }}
                                                        </td>

                                                        <td class="text-center align-middle">
                                                            {{ number_format($payment->amount, 0, ',', '.') }} VNĐ
                                                        </td>

                                                        <td class="text-center align-middle">
                                                            @if ($payment->paymentMethod->type === 'cod' && $payment->status === 'pending')
                                                                <button class="btn btn-success btn-sm btn-confirm-cod"
                                                                    data-id="{{ $payment->id }}">
                                                                    Xác nhận đã thanh toán
                                                                </button>
                                                            @else
                                                                @if ($payment->status === 'pending')
                                                                    <span class="badge badge-warning"
                                                                        style="padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; color: #fff">
                                                                        Chờ thanh toán</span>
                                                                @elseif($payment->status === 'success')
                                                                    <span class="badge badge-success"
                                                                        style="padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; color: #fff">
                                                                        Đã thanh toán</span>
                                                                @else
                                                                    <span class="badge badge-danger"
                                                                        style="padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; color: #fff">
                                                                        Thất bại</span>
                                                                @endif
                                                            @endif
                                                        </td>

                                                        <td class="text-center">
                                                            <button class="btn btn-primary btn-sm btn-view-payment"
                                                                data-id="{{ $payment->id }}">
                                                                <i class="fa fa-eye"></i> Xem chi tiết
                                                            </button>
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
    <div class="modal fade" id="ticketDetailModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết vé</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Mã giao dịch</th>
                            <td id="d-transaction-code"></td>
                        </tr>
                        <tr>
                            <th>Mã đặt vé</th>
                            <td id="d-ticket-code"></td>
                        </tr>
                        <tr>
                            <th>Hành khách</th>
                            <td id="d-passenger-name"></td>
                        </tr>
                        <tr>
                            <th>Số điện thoại</th>
                            <td id="d-passenger-phone"></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td id="d-passenger-email"></td>
                        </tr>
                        <tr>
                            <th>Ghế</th>
                            <td id="d-seat"></td>
                        </tr>
                        <tr>
                            <th>Thời gian chạy</th>
                            <td id="d-time"></td>
                        </tr>

                        <tr>
                            <th>Phương thức thanh toán</th>
                            <td id="d-payment-method"></td>
                        </tr>
                        <tr>
                            <th>Số tiền</th>
                            <td id="d-amount"></td>
                        </tr>
                        <tr>
                            <th>Ngày tạo giao dịch</th>
                            <td id="d-created-at"></td>
                        </tr>
                        <tr>
                            <th>Ngày thanh toán</th>
                            <td id="d-paid-at"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
