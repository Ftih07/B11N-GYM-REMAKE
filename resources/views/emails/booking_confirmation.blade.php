<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Booking</title>
</head>
<body>
    <h2>Halo, {{ $booking->name }}</h2>
    <p>Terima kasih telah melakukan booking di **Kost Istana Merdeka 3**.</p>

    <p><strong>Kode Booking:</strong> #{{ $booking->id }}</p>
    <p><strong>Email:</strong> {{ $booking->email }}</p>
    <p><strong>No. HP:</strong> {{ $booking->phone }}</p>
    <p><strong>Tanggal:</strong> {{ $booking->date }}</p>
    <p><strong>Tipe Kamar:</strong> {{ $booking->room_type }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ $booking->payment }}</p>
    <p><strong>Status Pembayaran:</strong> {{ ucfirst($booking->status) }}</p>

    <p>Mohon tunggu konfirmasi dari admin. Jika ada pertanyaan, hubungi kami.</p>

    <p>Terima kasih,</p>
    <p><strong>Kost Istana Merdeka 3</strong></p>
</body>
</html>
