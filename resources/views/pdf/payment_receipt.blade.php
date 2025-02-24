
<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { font-size: 20px; font-weight: bold; margin-bottom: 20px; }
        .content { font-size: 16px; }
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
</body>
</html>
