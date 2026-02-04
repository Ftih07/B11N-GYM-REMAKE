<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Booking - Kost Istana Merdeka 3</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* RESET & BASE */
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #1a1a1a;
            /* Background Gelap Industrial */
            margin: 0;
            padding: 40px 0;
            color: #333;
        }

        /* CONTAINER CARD - Tajam & Kokoh */
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 0;
            border: none;
            border-radius: 0px !important;
            /* STRICTLY SQUARE */
            box-shadow: 10px 10px 0px rgba(0, 0, 0, 0.5);
            /* Hard Shadow, bukan soft blur */
        }

        /* HEADER - Bold & Heavy */
        .header {
            background-color: #000000;
            color: #D4AF37;
            /* K1NG Gold Color */
            padding: 30px 20px;
            text-align: center;
            border-bottom: 5px solid #D4AF37;
        }

        .header h2 {
            font-family: 'Oswald', sans-serif;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
            font-weight: 700;
        }

        /* CONTENT */
        .content {
            padding: 40px;
        }

        .content p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .highlight-name {
            font-weight: 700;
            color: #000;
            text-decoration: underline;
            text-decoration-color: #D4AF37;
            text-decoration-thickness: 3px;
        }

        /* DATA GRID / SPEC SHEET STYLE */
        .booking-details-box {
            border: 2px solid #000;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .detail-row {
            display: flex;
            border-bottom: 1px solid #ddd;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .label {
            width: 40%;
            background-color: #f4f4f4;
            padding: 12px 15px;
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
            font-size: 14px;
            font-weight: 500;
            border-right: 1px solid #ddd;
            color: #555;
            letter-spacing: 1px;
        }

        .value {
            width: 60%;
            padding: 12px 15px;
            font-weight: 500;
            color: #000;
            font-family: 'Roboto', monospace;
            /* Monospace vibe for data */
        }

        /* STATUS BADGE */
        .status-badge {
            display: inline-block;
            background-color: #000;
            color: #D4AF37;
            padding: 4px 10px;
            font-family: 'Oswald', sans-serif;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* FOOTER */
        .footer {
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #888;
            border-top: 2px solid #000;
        }

        .brand-footer {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Booking Confirmation</h2>
        </div>

        <div class="content">
            <p>Halo <span class="highlight-name">{{ $booking->name }}</span>,</p>
            <p>Permintaan booking Anda telah masuk ke sistem kami. Berikut adalah <strong>Booking Manifest</strong> Anda untuk akses ke <strong>Kost Istana Merdeka 3</strong>.</p>

            <div class="booking-details-box">
                <div class="detail-row">
                    <div class="label">Kode Booking</div>
                    <div class="value">#{{ $booking->id }}</div>
                </div>
                <div class="detail-row">
                    <div class="label">Email</div>
                    <div class="value">{{ $booking->email }}</div>
                </div>
                <div class="detail-row">
                    <div class="label">Check-in</div>
                    <div class="value">{{ $booking->date }}</div>
                </div>
                <div class="detail-row">
                    <div class="label">Kamar</div>
                    <div class="value">
                        Room {{ $booking->room_number }} <br>
                        <span style="font-size:12px; color:#666;">({{ $booking->room_type }})</span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="label">Pembayaran</div>
                    <div class="value">{{ $booking->payment }}</div>
                </div>
                <div class="detail-row">
                    <div class="label">Status</div>
                    <div class="value">
                        <span class="status-badge">{{ ucfirst($booking->status) }}</span>
                    </div>
                </div>
            </div>

            <p>Harap tunjukkan email ini saat kedatangan. Jika ada perubahan data, segera hubungi admin.</p>

            <p style="margin-top: 30px;">
                Stay Safe,<br>
                <strong style="font-family: 'Oswald', sans-serif; font-size: 18px; text-transform: uppercase;">Management Team</strong>
            </p>
        </div>

        <div class="footer">
            <p class="brand-footer">Kost Istana Merdeka 3</p>
            <p>&copy; 2026 Part of The Empire Network. All Rights Reserved.</p>
        </div>
    </div>
</body>

</html>