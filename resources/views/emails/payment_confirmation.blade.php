<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pembayaran Membership - B1NG Empire</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500;900&display=swap" rel="stylesheet">
    <style>
        /* BASE STYLE */
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #121212;
            /* Darker than Kost layout */
            margin: 0;
            padding: 40px 0;
            color: #ccc;
        }

        /* CARD CONTAINER */
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #1e1e1e;
            /* Dark Card */
            border: 1px solid #333;
            border-radius: 0px;
            /* Square */
            box-shadow: 12px 12px 0px #D90429;
            /* Red Hard Shadow */
        }

        /* HEADER */
        .header {
            background-color: #000;
            padding: 30px;
            text-align: center;
            border-bottom: 4px solid #D90429;
            /* B11N Red */
        }

        .header h2 {
            font-family: 'Oswald', sans-serif;
            font-size: 32px;
            text-transform: uppercase;
            margin: 0;
            color: #fff;
            letter-spacing: 2px;
            font-weight: 700;
        }

        .sub-header {
            font-size: 14px;
            color: #D90429;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-top: 5px;
            font-weight: bold;
        }

        /* CONTENT */
        .content {
            padding: 40px;
            color: #e0e0e0;
        }

        .greeting {
            font-size: 20px;
            margin-bottom: 20px;
            color: #fff;
        }

        .highlight {
            color: #D90429;
            font-weight: 900;
        }

        /* IMAGE DISPLAY (Digital Card Look) */
        .membership-card-display {
            background: #000;
            padding: 10px;
            border: 1px dashed #444;
            display: inline-block;
            margin: 20px 0;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }

        .membership-card-display img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
            border: 1px solid #333;
        }

        .card-caption {
            margin-top: 10px;
            font-size: 11px;
            text-transform: uppercase;
            color: #666;
            font-family: 'Oswald', sans-serif;
        }

        /* DATA GRID (Dark Version) */
        .data-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
            border-top: 2px solid #333;
            padding-top: 20px;
        }

        .data-item {
            background: #252525;
            padding: 15px;
            border-left: 3px solid #444;
        }

        .data-item.accent {
            border-left: 3px solid #D90429;
        }

        .data-label {
            font-family: 'Oswald', sans-serif;
            font-size: 12px;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 5px;
            display: block;
        }

        .data-value {
            font-size: 16px;
            font-weight: 500;
            color: #fff;
            font-family: 'Roboto', monospace;
        }

        /* FOOTER */
        .footer {
            background-color: #000;
            text-align: center;
            padding: 20px;
            border-top: 1px solid #333;
            font-size: 12px;
            color: #666;
        }

        .footer strong {
            color: #fff;
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Payment Confirmed</h2>
            <div class="sub-header">B1NG Empire Fitness</div>
        </div>

        <div class="content">
            <div class="greeting">
                Halo, <span class="highlight">{{ $payment->name }}</span>
            </div>

            <p>Akses membership Anda telah aktif. Anda kini resmi tergabung dalam ekosistem B11N & K1NG Gym. Bersiaplah untuk melampaui batas.</p>

            <div class="data-grid">
                <div class="data-item">
                    <span class="data-label">Tipe Membership</span>
                    <span class="data-value">{{ $payment->membership_type }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Order ID</span>
                    <span class="data-value">#{{ $payment->order_id }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Metode Bayar</span>
                    <span class="data-value">{{ strtoupper($payment->payment) }}</span>
                </div>
                <div class="data-item accent">
                    <span class="data-label">Status</span>
                    <span class="data-value" style="color: #D90429;">{{ ucfirst($payment->status) }}</span>
                </div>
            </div>

            <p style="margin-top: 30px; font-size: 14px;">
                Jika ada kendala akses, tunjukkan email ini kepada resepsionis atau hubungi support kami segera.
            </p>
        </div>

        <div class="footer">
            <p><strong>B11N & K1NG Gym</strong></p>
            <p>Train Hard. Stay Humble. <br>&copy; 2026 B1NG Empire.</p>
        </div>
    </div>
</body>

</html>