@extends('layouts.client')

@section('title', 'Chuyển khoản ngân hàng')

@section('content')
    <div class="container my-4">
        <div class="card shadow p-4">
            <h3 class="mb-3 text-primary">Thanh toán chuyển khoản ngân hàng</h3>

            <p>Vui lòng chuyển <strong class="text-danger">{{ number_format($transfer->amount) }} VNĐ</strong> vào thông tin
                ngân hàng dưới đây:</p>

            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Ngân hàng:</strong> {{ $transfer->bank_name }}</li>
                <li class="list-group-item"><strong>Số tài khoản:</strong> {{ $transfer->account_number }}</li>
                <li class="list-group-item"><strong>Chủ tài khoản:</strong> {{ $transfer->account_name }}</li>
            </ul>

            <div class="mb-3">
                <strong>QR Code:</strong><br>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $transfer->account_number }}-{{ $transfer->amount }}"
                    alt="QR Code">
            </div>

            <p>Ghế được giữ <strong id="countdown"></strong> phút. Nếu quá thời gian này, booking sẽ bị hủy tự động.</p>

            {{-- Optional: khách bấm đã chuyển --}}
            <form action="{{ route('booking.bank-transfer.confirm') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <button type="submit" class="btn btn-success">Tôi đã chuyển tiền</button>
            </form>
        </div>
    </div>

    <script>
        let expiresAt = new Date("{{ $transfer->expires_at->format('Y-m-d H:i:s') }}").getTime();

        let x = setInterval(function() {
            let now = new Date().getTime();
            let distance = expiresAt - now;

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "Hết thời gian!";
                alert("Thời gian thanh toán đã hết. Vé bị hủy.");
                window.location.href = "{{ route('home') }}";
            } else {
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                document.getElementById("countdown").innerHTML = minutes + " phút " + seconds + " giây";
            }
        }, 1000);
    </script>
@endsection
