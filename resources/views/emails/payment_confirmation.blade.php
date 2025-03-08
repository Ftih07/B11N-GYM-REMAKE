<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Pembayaran Membership</title>
</head>
<body>
    <h2>Halo, {{ $payment->name }}</h2>

    <p>Terima kasih telah melakukan pembayaran untuk membership <strong>{{ $payment->membership_type }}</strong>.</p>
    <p><strong>Order ID:</strong> {{ $payment->order_id }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ strtoupper($payment->payment) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>

    <p><img src="{{ asset($membershipImage) }}" alt="Membership Image" width="200"></p>

    <p>Pembayaran anda telah kami konfirmasi. Jika ada pertanyaan, silakan hubungi kami.</p>

    <p>Salam,<br>B11N & K1NG Gym</p>
</body>
</html>
