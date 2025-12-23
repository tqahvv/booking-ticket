<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans;
        }

        .ticket {
            border: 2px dashed #333;
            padding: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
        }
    </style>
</head>

<body>
    <div class="ticket">
        <h2>VÉ XE KHÁCH</h2>
        <p><strong>Mã vé:</strong> {{ $booking->code }}</p>

        <p>
            <strong>Tuyến:</strong>
            {{ $booking->schedule->route->origin->city }}
            →
            {{ $booking->schedule->route->destination->city }}
        </p>

        <p>
            <strong>Ngày đi:</strong>
            {{ \Carbon\Carbon::parse($booking->schedule->departure_datetime)->format('d/m/Y H:i') }}
        </p>

        <table>
            <tr>
                <th>Hành khách</th>
                <th>Ghế</th>
            </tr>
            @foreach ($booking->passengers as $p)
                <tr>
                    <td>{{ $p->passenger_name }}</td>
                    <td>{{ $p->seat_number }}</td>
                </tr>
            @endforeach
        </table>

        <p style="margin-top:15px">
            {!! QrCode::size(120)->generate($booking->code) !!}
        </p>
    </div>
</body>

</html>
