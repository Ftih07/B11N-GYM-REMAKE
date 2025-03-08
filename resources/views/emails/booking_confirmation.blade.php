<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Booking - Kost Istana Merdeka 3</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .content p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
        }
        .booking-details {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .booking-details p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Konfirmasi Booking</h2>
        </div>

        <div class="content">
            <p>Halo <strong>{{ $booking->name }}</strong>,</p>
            <p>Terima kasih telah melakukan booking di <strong>Kost Istana Merdeka 3</strong>. Berikut adalah detail pemesanan Anda:</p>

            <div class="booking-details">
                <p><strong>Kode Booking:</strong> #{{ $booking->id }}</p>
                <p><strong>Email:</strong> {{ $booking->email }}</p>
                <p><strong>No. HP:</strong> {{ $booking->phone }}</p>
                <p><strong>Tanggal Check-in:</strong> {{ $booking->date }}</p>
                <p><strong>Nomor Kamar:</strong> {{ $booking->room_number }}</p>
                <p><strong>Tipe Kamar:</strong> {{ $booking->room_type }}</p>
                <p><strong>Metode Pembayaran:</strong> {{ $booking->payment }}</p>
                <p><strong>Status Pembayaran:</strong> {{ ucfirst($booking->status) }}</p>
            </div>

            <p>Kami tunggu kedatangan anda di Kost Istana Merdeka 03. Jika Anda memiliki pertanyaan atau ingin mengubah reservasi, silakan hubungi kami.</p>

            <p>Salam hangat,</p>
            <p><strong>Kost Istana Merdeka 3</strong></p>
        </div>

        <div class="footer">
            <p>&copy; 2025 Kost Istana Merdeka 3 | Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>