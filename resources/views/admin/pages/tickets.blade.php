@extends('layouts.admin')

@section('title', 'Quản lý vé xe')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách vé xe</h3>
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
                                            Trang quản lý đặt chỗ cho phép nhà xe xem và cập nhật danh sách vé xe.
                                        </p>
                                        <table id="datatable-buttons" class="table table-striped table-bordered"
                                            style="width: 100%;">
                                            <thead>
                                                <tr role="row">
                                                    <th>Mã vé</th>
                                                    <th>Mã đặt vé</th>
                                                    <th>Ghế</th>
                                                    <th>Thời gian chạy</th>
                                                    <th>Trạng thái</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($tickets as $ticket)
                                                    <tr id="ticket-row-{{ $ticket->id }}">
                                                        <td>{{ $ticket->ticket_code }}</td>
                                                        <td>{{ $ticket->booking->code ?? 'N/A' }}</td>
                                                        <td>{{ $ticket->seat_number ?? '-' }}</td>
                                                        <td>
                                                            {{ $ticket->valid_from }} → <br>
                                                            {{ $ticket->valid_to }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $statusText = [
                                                                    'unused' => 'Chưa sử dụng',
                                                                    'used' => 'Đã sử dụng',
                                                                    'expired' => 'Hết hạn',
                                                                    'cancelled' => 'Đã hủy',
                                                                ];
                                                            @endphp

                                                            <select class="form-control ticket-status"
                                                                data-id="{{ $ticket->id }}" @disabled(in_array($ticket->status, ['used', 'expired']))>

                                                                @foreach ($statusText as $key => $label)
                                                                    <option value="{{ $key }}"
                                                                        @selected($ticket->status === $key)>
                                                                        {{ $label }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <td class="text-center">
                                                            <button class="btn btn-primary btn-sm btn-view-ticket"
                                                                data-id="{{ $ticket->id }}">
                                                                <i class="fa fa-eye"></i> Xem chi tiết
                                                            </button>
                                                        </td>
                                                        <td class="text-center">
                                                            <button class="btn btn-danger btn-sm btn-delete-ticket"
                                                                @disabled($ticket->status === 'used') data-id="{{ $ticket->id }}"
                                                                data-url="{{ route('admin.tickets.delete', $ticket->id) }}">
                                                                <i class="fa fa-trash"></i> Xóa
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
                            <th>Mã vé</th>
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
                            <th>Trạng thái</th>
                            <td id="d-status"></td>
                        </tr>
                        <tr>
                            <th>Phương thức thanh toán</th>
                            <td id="d-payment-method"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
