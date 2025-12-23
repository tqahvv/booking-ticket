<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            padding: 20px;
        }

        .box {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background: #f44336;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>🎉 Đặt vé thành công!</h2>

        <p><strong>Mã vé:</strong> {{ $booking->code }}</p>
        <p>
            <strong>Tuyến:</strong>
            {{ $booking->schedule->route->origin->city }}
            →
            {{ $booking->schedule->route->destination->city }}
        </p>

        <p><strong>Ngày đi:</strong>
            {{ \Carbon\Carbon::parse($booking->schedule->departure_datetime)->format('d/m/Y H:i') }}
        </p>

        <h3>👤 Thông tin hành khách</h3>
        <table>
            <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>SĐT</th>
                    <th>Email</th>
                    <th>Ghế</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($booking->passengers as $p)
                    <tr>
                        <td>{{ $p->passenger_name }}</td>
                        <td>{{ $p->passenger_phone }}</td>
                        <td>{{ $p->passenger_email }}</td>
                        <td>{{ $p->seat_number }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top: 20px">
            📎 Thông tin vé đã được xác nhận thành công.
        </p>

        <p>Chúc bạn có chuyến đi an toàn! 🚍</p>
    </div>
</body>

</html>
