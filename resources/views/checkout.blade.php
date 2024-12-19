<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .cart-details,
        .payment-methods {
            width: 48%;
        }

        .cart-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-details th,
        .cart-details td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .total {
            font-size: 1.25rem;
            font-weight: bold;
            margin-top: 20px;
        }

        .payment-methods a {
            display: block;
            text-align: center;
            margin: 10px;
            padding: 15px;
            font-size: 1rem;
            font-weight: bold;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .payment-methods .qris {
            background-color: #4CAF50;
        }

        .payment-methods .transfer {
            background-color: #2196F3;
        }

        .payment-methods a:hover {
            opacity: 0.8;
        }

        /* Modal Styles */
        .modal {
            display: none;
            /* Hide modal by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            max-width: 500px;
            width: 90%;
            text-align: center;
        }

        .modal-header {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .modal-body {
            margin-bottom: 20px;
        }

        .close-modal {
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .payment-btn {
            display: inline-block;
            margin: 10px 20px;
            padding: 10px 20px;
            background-color: #1d4ed8;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
        }

        .payment-btn:hover {
            background-color: #2563eb;
        }
    </style>
</head>

<body>
    <h1 class="text-2xl font-bold mb-4 text-center">Payment Options</h1>

    <div class="container">
        <!-- Cart Details -->
        <div class="cart-details">
            <h2 class="text-xl font-bold">Order Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $id => $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="total">
                Total: ${{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2) }}
            </div>

            <div class="payment-confirmation mt-10 p-6 bg-white shadow-lg rounded-lg mb-[100px]">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Confirm Your Payment</h3>
                <p class="text-gray-600 mb-6">
                    Please confirm your payment via WhatsApp or email using the options below. Make sure to include your <span class="font-medium text-gray-800">Order ID</span> for quick verification.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- WhatsApp Button -->
                    <a href="https://wa.me/+6281226110988?text=I%20would%20like%20to%20confirm%20my%20payment%20for%20order%20ID%20"
                        class="flex items-center justify-center w-full sm:w-auto px-6 py-3 bg-green-500 text-white font-medium rounded-lg shadow-md hover:bg-green-600 transition">
                        Confirm via WhatsApp
                    </a>

                    <!-- Email Button -->
                    <a href="mailto:payment@example.com?subject=Payment%20Confirmation%20for%20Order%20&body=Dear%20Team,%0A%0AI%20would%20like%20to%20confirm%20my%20payment%20for%20order%20ID%20.%0A%0ABest%20regards,"
                        class="flex items-center justify-center w-full sm:w-auto px-6 py-3 bg-blue-500 text-white font-medium rounded-lg shadow-md hover:bg-blue-600 transition">
                        Confirm via Email
                    </a>
                </div>
            </div>

        </div>

        <!-- Payment Methods -->
        <div class="payment-methods">
            <h2 class="text-xl font-bold text-center">Choose Payment Method</h2>
            <a href="#" class="payment-btn" id="qris-btn">Pay with QRIS</a>
            <a href="#" class="payment-btn" id="transfer-btn">Pay with Transfer</a>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="qris-modal">
        <div class="modal-content">
            <div class="modal-header">QRIS Payment</div>
            <div class="modal-body">
                <img src="path-to-your-qris-image.png" alt="QRIS Code" style="max-width: 100%; border-radius: 10px;">
                <p>Please scan the QR code to complete your payment.</p>
            </div>
            <button class="close-modal" id="close-qris">Close</button>
        </div>
    </div>

    <div class="modal" id="transfer-modal">
        <div class="modal-content">
            <div class="modal-header">Transfer Payment</div>
            <div class="modal-body">
                <img src="path-to-your-qris-image.png" alt="QRIS Code" style="max-width: 100%; border-radius: 10px;">
                <p>Please scan the QR code to complete your payment.</p>
            </div>
            <button class="close-modal" id="close-transfer">Close</button>
        </div>
    </div>

    <script>
        // Get elements
        const qrisBtn = document.getElementById('qris-btn');
        const qrisModal = document.getElementById('qris-modal');
        const closeQrisBtn = document.getElementById('close-qris');

        // Open modal when QRIS button is clicked
        qrisBtn.addEventListener('click', (e) => {
            e.preventDefault();
            qrisModal.style.display = 'flex';
        });

        // Close modal when close button is clicked
        closeQrisBtn.addEventListener('click', () => {
            qrisModal.style.display = 'none';
        });

        // Close modal when clicking outside the content
        window.addEventListener('click', (e) => {
            if (e.target === qrisModal) {
                qrisModal.style.display = 'none';
            }
        });

        // Get elements
        const tranferBtn = document.getElementById('transfer-btn');
        const transferModal = document.getElementById('transfer-modal');
        const closeTransferBtn = document.getElementById('close-transfer');

        // Open modal when QRIS button is clicked
        tranferBtn.addEventListener('click', (e) => {
            e.preventDefault();
            transferModal.style.display = 'flex';
        });

        // Close modal when close button is clicked
        closeTransferBtn.addEventListener('click', () => {
            transferModal.style.display = 'none';
        });

        // Close modal when clicking outside the content
        window.addEventListener('click', (e) => {
            if (e.target === transferModal) {
                transferModal.style.display = 'none';
            }
        });
    </script>
</body>

</html>