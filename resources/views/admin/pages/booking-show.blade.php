@extends('layouts.admin')

@section('title', 'Chi tiết đặt chỗ')

@section('content')
    <div class="right_col" role="main">
        <div class="x_panel">

            {{-- HEADER --}}
            <div class="x_title d-flex justify-content-between align-items-center">
                <h2>
                    Chi tiết đặt chỗ
                    <small class="text-muted">#{{ $booking->code }}</small>
                </h2>

                <span class="badge bg-{{ $booking->status_color }} text-light" style="font-size: 14px; padding: 8px 12px;">
                    {{ $booking->status_label }}
                </span>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                {{-- THÔNG TIN ĐẶT VÉ --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3">📌 Thông tin đặt vé</h4>

                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Khách hàng:</strong>
                                    {{ $booking->passengers->first()->passenger_name }}
                                </p>

                                <p><strong>Ngày đặt:</strong>
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            <div class="col-md-6">
                                <p><strong>Số lượng vé:</strong>
                                    {{ $booking->num_passengers }}
                                </p>

                                <p><strong>Tổng tiền:</strong>
                                    <span class="text-danger fw-bold">
                                        {{ number_format($booking->final_price, 0, ',', '.') }} VNĐ
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DANH SÁCH HÀNH KHÁCH --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3">🧍 Danh sách hành khách</h4>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Họ tên</th>
                                        <th>SĐT</th>
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
                                            <td>{{ $p->passenger_email ?? '-' }}</td>
                                            <td class="text-center">{{ $p->seat_number }}</td>
                                            <td>{{ $p->pickupStop->location->name }}</td>
                                            <td>{{ $p->dropoffStop->location->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- CẬP NHẬT TRẠNG THÁI --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3">⚙️ Cập nhật trạng thái</h4>

                        <select id="booking-status" data-id="{{ $booking->id }}"
                            data-url="{{ route('admin.bookings.updateStatus', $booking->id) }}" class="form-control"
                            style="max-width: 300px">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>
                                Chờ xử lý
                            </option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>
                                Đã xác nhận
                            </option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>
                                Đã hủy
                            </option>
                            <option value="expired" {{ $booking->status == 'expired' ? 'selected' : '' }}>
                                Hết hạn
                            </option>
                        </select>
                    </div>
                </div>

                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                    ← Quay lại danh sách
                </a>

            </div>
        </div>
    </div>
@endsection
