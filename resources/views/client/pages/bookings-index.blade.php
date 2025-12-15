@extends('layouts.client')

@section('title', 'Đặt chỗ của tôi')
@section('content')
    <div class="container my-5">
        <h2 class="mb-4 text-center">Đặt chỗ của tôi</h2>

        @if (!$user)
            <div class="card shadow-lg mb-4 p-4">
                <h4 class="mb-3">Tra cứu đặt chỗ</h4>
                <form method="GET" action="{{ route('booking.index') }}" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Nhập email">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" placeholder="Nhập số điện thoại">
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary px-4" style="margin-top: 10px">Tra cứu</button>
                    </div>
                </form>
            </div>
        @endif

        @if ($bookings->isEmpty())
            <div class="alert alert-warning text-center mt-4">
                Không tìm thấy đặt chỗ nào.
            </div>
        @else
            @foreach ($bookings as $booking)
                <div class="card shadow-lg mb-4 border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold">Mã đặt chỗ: {{ $booking->code }}</h5>
                            <span class="badge bg-{{ $booking->status_color }} text-light">
                                {{ $booking->status_label }}
                            </span>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Ngày đặt:</strong>
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y H:i') }}</div>
                            <div class="col-md-3"><strong>Tổng tiền:</strong>
                                {{ number_format($booking->total_price, 0, ',', '.') }} VNĐ
                            </div>
                            <div class="col-md-3"><strong>Số khách:</strong> {{ $booking->num_passengers }}</div>
                            <div class="col-md-3">
                                <strong>Thanh toán:</strong>
                                {{ $booking->paymentMethod->name ?? 'Chưa chọn' }}
                            </div>
                        </div>

                        <h6 class="fw-bold mt-3">Hành khách</h6>
                        <table class="table table-bordered table-striped mt-2">
                            <thead class="table-light">
                                <tr>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Ghế</th>
                                    <th>Điểm đón</th>
                                    <th>Điểm trả</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($booking->passengers as $p)
                                    <tr>
                                        <td>{{ $p->passenger_name }}</td>
                                        <td>{{ $p->passenger_email }}</td>
                                        <td>{{ $p->passenger_phone }}</td>
                                        <td>{{ $p->seat_number }}</td>
                                        <td>{{ $p->pickupStop->location->name ?? 'N/A' }}</td>
                                        <td>{{ $p->dropoffStop->location->name ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h6 class="fw-bold mt-4">Vé đã phát hành</h6>
                        @if ($booking->tickets->isEmpty())
                            <p class="text-muted">Chưa có vé phát hành.</p>
                        @else
                            <table class="table table-bordered table-striped mt-2">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã vé</th>
                                        <th>Ghế</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày phát hành</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($booking->tickets as $ticket)
                                        <tr>
                                            <td>{{ $ticket->ticket_code }}</td>
                                            <td>{{ $ticket->seat_number }}</td>
                                            <td>
                                                @if ($ticket->status == 'unused')
                                                    <span class="badge bg-success text-light">Chưa sử dụng</span>
                                                @elseif($ticket->status == 'used')
                                                    <span class="badge bg-secondary text-light">Đã sử dụng</span>
                                                @elseif($ticket->status == 'cancelled')
                                                    <span class="badge bg-danger text-light">Đã hủy</span>
                                                @else
                                                    <span class="badge bg-warning text-light">Không rõ</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($ticket->issued_at)->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        <div class="d-flex gap-3 mt-4">
                            <a href="{{ route('home') }}" class="btn btn-outline-primary">
                                Về trang chủ
                            </a>

                            @if ($booking->canCancel())
                                <button class="btn btn-danger btn-cancel-booking"
                                    style="margin-left: 10px; display: flex; justify-content: center; align-items: center"
                                    data-id="{{ $booking->id }}" data-email="{{ request('email') }}"
                                    data-phone="{{ request('phone') }}">
                                    Hủy vé
                                </button>
                            @elseif($booking->paymentMethod && $booking->paymentMethod->type === 'vnpay')
                                <span class="text-muted fst-italic"
                                    style="padding-left: 10px; display: flex; justify-content: center; align-items: center">
                                    Vé thanh toán online hiện chưa hỗ trợ hủy
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
