
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
    </div>

    @php
        $secretKey = env('QR_SECRET_KEY', 'default_secret'); // Store this in .env
        $qrCodeData = $bill->id . '|' . $bill->user->name . '|' .'A14';
        $hashedCode = hash_hmac('sha256', $qrCodeData, $secretKey);
    @endphp
    
    <!-- QR Codes Section -->
    <div class="qr-section row">
        
            <div class="col qr-box">
                <p><strong>Seat No: A14</strong></p>
                <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(100)->generate('Bill: ' . $bill->id . ', A14,'.$bill->user->name . ', ' . $hashedCode)) }}">
            </div>
            <div class="col qr-box">
                <p><strong>Seat No: A14</strong></p>
                <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(100)->generate('Bill: ' . $bill->id . ', A14,'.$bill->user->name . ', ' . $hashedCode)) }}">
            </div>
            <div class="col qr-box">
                <p><strong>Seat No: A14</strong></p>
                <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(100)->generate('Bill: ' . $bill->id . ', A14,'.$bill->user->name . ', ' . $hashedCode)) }}">
            </div>
           
            
           
           
        
    </div>
</body>
</html>