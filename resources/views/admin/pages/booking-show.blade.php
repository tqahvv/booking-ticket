@extends('layouts.admin')

@section('title', 'Chi tiết đặt chỗ')

@section('content')
    <div class="right_col" role="main">

        <div class="x_panel">
            <div class="x_title">
                <h2>Chi tiết đặt chỗ #{{ $booking->code }}</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <h4>Thông tin chung</h4>
                <p><strong>Khách hàng:</strong> {{ $booking->passengers->first()->passenger_name }}</p>
                <p><strong>Trạng thái:</strong>
                    <span class="badge bg-{{ $booking->status_color }} text-light">
                        {{ $booking->status_label }}
                    </span>
                </p>

                <hr>

                <h4>Hành khách</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Họ tên</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th>Ghế</th>
                            <th>Điểm đón</th>
                            <th>Điểm trả</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking->passengers as $p)
                            <tr>
                                <td>{{ $p->passenger_name }}</td>
                                <td>{{ $p->passenger_phone }}</td>
                                <td>{{ $p->passenger_email }}</td>
                                <td>{{ $p->seat_number }}</td>
                                <td>{{ $p->pickupStop->location->name }}</td>
                                <td>{{ $p->dropoffStop->location->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($booking->bankTransfer)
                    <hr>
                    <h4>Chuyển khoản</h4>
                    <p><strong>Số tiền:</strong> {{ number_format($booking->bankTransfer->amount, 0, ',', '.') }} VND</p>
                    <p><strong>Hết hạn:</strong> {{ $booking->bankTransfer->expires_at }}</p>

                    @if (!$booking->bankTransfer->confirmed_at)
                        <button class="btn btn-success" id="btn-confirm-transfer" data-id="{{ $booking->id }}"
                            data-url="{{ route('admin.bookings.confirmTransfer', $booking->id) }}">
                            Xác nhận đã nhận tiền
                        </button>
                    @endif
                @endif

                <hr>

                <h4>Cập nhật trạng thái</h4>
                <select id="booking-status" data-id="{{ $booking->id }}" class="form-control" style="max-width:300px"
                    data-url="{{ route('admin.bookings.updateStatus', $booking->id) }}">
                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="waiting_transfer" {{ $booking->status == 'waiting_transfer' ? 'selected' : '' }}>Chờ
                        chuyển
                        khoản</option>
                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    <option value="expired" {{ $booking->status == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                </select>

            </div>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary mb-3" style="margin-top: 15px">
                ← Quay lại danh sách
            </a>
        </div>

    </div>
@endsection
