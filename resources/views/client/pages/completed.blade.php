@extends('layouts.client')

@section('title', 'Đặt vé thành công')

@section('content')
    <div class="container my-4">
        <div class="card shadow p-4">
            <h3 class="mb-3 text-success">Đặt vé thành công</h3>

            <div class="mb-4 p-3 border rounded bg-light">
                <h5 class="fw-bold mb-2">Tóm tắt đặt vé</h5>

                <p><strong>Mã đặt chỗ:</strong> {{ $booking->code }}</p>
                <p><strong>Tuyến:</strong> {{ $booking->schedule->route->origin->name }} →
                    {{ $booking->schedule->route->destination->name }}</p>
                <p><strong>Ngày khởi hành:</strong>
                    {{ \Carbon\Carbon::parse($booking->schedule->departure_datetime)->format('d/m/Y H:i') }}
                </p>
                <p><strong>Khách hàng:</strong> {{ $booking->passengers->first()->passenger_name }}</p>
                <p><strong>Số điện thoại:</strong> {{ $booking->passengers->first()->passenger_phone }}</p>
                <p><strong>Ghế:</strong> {{ $booking->passengers->pluck('seat_number')->join(', ') }}</p>
                <p><strong>Tổng tiền:</strong> <span class="text-danger fw-bold">{{ number_format($booking->total_price) }}
                        VNĐ</span></p>
                <p><strong>Trạng thái thanh toán:</strong>
                    @if ($booking->paid)
                        <span class="badge bg-success text-light">Đã thanh toán</span>
                    @else
                        <span class="badge bg-warning text-light">Chưa thanh toán (COD/Chuyển khoản)</span>
                    @endif
                </p>
            </div>

            <h5 class="fw-bold mt-4">Danh sách vé</h5>
            <ul>
                @foreach ($booking->tickets as $ticket)
                    <li>
                        Mã vé: {{ $ticket->ticket_code }} – Ghế: {{ $ticket->seat_number }}
                    </li>
                @endforeach
            </ul>

            <div class="d-flex gap-3">
                <a href="{{ route('home') }}" class="btn btn-outline-primary">Về trang chủ</a>
                {{-- <a href="" class="btn btn-primary">In vé
                    / Tải PDF</a> --}}
            </div>
        </div>
    </div>
@endsection
