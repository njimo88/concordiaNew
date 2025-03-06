
<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { font-size: 20px; font-weight: bold; margin-bottom: 20px; }
        .content { font-size: 16px; }
        .qr-section { display: flex; flex-wrap: wrap; margin-top: 20px; }
        .qr-box { margin: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">Payment Receipt</div>
    <div class="content">
        <p><strong>Bill ID:</strong> {{ $bill->id }}</p>
        <p><strong>User:</strong> {{ $bill->user->name }}</p>
        <p><strong>Amount Paid:</strong> ${{ number_format($bill->payment_total_amount, 2) }}</p>
        <p><strong>Payment Method:</strong> {{ $bill->payment_method }}</p>
        <p>Thank you for your payment!</p>

        @foreach ($myreservationCopy as $reservation)
        <p> <strong>Reservation ID:</strong> {{ $reservation->id_reservation }} 
            <strong>seat number :</strong> {{ $reservation->seat->seat_number }}
            <strong>Status:</strong> {{ $reservation->status }}
            <strong>spectacle ID :</strong> {{ $reservation->seat->id_spectacle }}
        </p>
        @php
            $secretKey = env('ApsXG5I63R8Ow', 'default_secret'); // Store this in .env
            $qrCodeData = $reservation->seat->id_spectacle. ', ' .$reservation->seat->seat_number. ', ' . $bill->user->name;
            $hashedCode = hash_hmac('sha256', $qrCodeData, $secretKey);
        @endphp
        <div class="qr-section row">
            <div class="col qr-box">
                <p><strong>Seat : {{$reservation->seat->seat_number}}  </strong></p>
                <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(100)->generate($qrCodeData.','.$hashedCode)) }}">
                
            </div>
        </div>
        @endforeach
        
    
    </div>


</body>
</html>