
<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
</head>
<body>
    <p>Dear {{ $bill->user->name }},</p>
    <p>Thank you for your payment. Your bill ID is <strong>{{ $bill->id }}</strong>.</p>
    <p>Please find the attached receipt.</p>
    <p>Best regards,</p>
    <p>Your Company</p>
</body>
</html>
