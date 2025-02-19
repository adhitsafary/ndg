<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
</head>

<body>
    <h2>Payment Gateway</h2>

    <form id="payment-form">
        <input type="text" id="name" placeholder="Nama Lengkap" required>
        <input type="email" id="email" placeholder="Email" required>
        <input type="text" id="phone" placeholder="No. Telepon" required>
        <input type="number" id="amount" placeholder="Jumlah Pembayaran" required>
        <button type="button" onclick="createPayment()">Bayar Sekarang</button>
    </form>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        function createPayment() {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const amount = document.getElementById('amount').value;

            // Kirim data ke server untuk mendapatkan snap_token
            fetch('/create-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        phone: phone,
                        amount: amount,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    // Panggil Snap.js untuk memulai pembayaran
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            alert('Pembayaran berhasil: ' + JSON.stringify(result));
                        },
                        onPending: function(result) {
                            alert('Pembayaran tertunda: ' + JSON.stringify(result));
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal: ' + JSON.stringify(result));
                        }
                    });
                });
        }
    </script>
</body>

</html>
