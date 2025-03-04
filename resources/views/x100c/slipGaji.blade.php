<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Desain layar normal */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
        }

        .header-left {
            text-align: left;
            font-size: 1rem;
        }

        .header-center {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .header-center img {
            width: 50px;
            /* Logo kecil */
            height: auto;
        }

        .header-center h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2b6cb0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            padding: 12px 20px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f7fafc;
            font-weight: 600;
        }

        .table td {
            background-color: #f9f9f9;
        }

        .total {
            background-color: #f7fafc;
            font-weight: 600;
            color: #2d3748;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #718096;
        }

        /* CSS untuk Media Print */
        @media print {
            body {
                font-family: Arial, sans-serif;
                background-color: #fff;
                color: #000;
            }

            .container {
                width: 100%;
                max-width: none;
                padding: 0;
                box-shadow: none;
            }

            .header {
                display: block;
                text-align: center;
            }

            .table th,
            .table td {
                padding: 10px;
            }

            .table th {
                background-color: #f7fafc;
            }

            .footer {
                font-size: 12px;
                margin-top: 20px;
                text-align: center;
                color: #333;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<br><br>

<body class="bg-gray-100">
    <div class="container">

        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <p>Nama: <span class="font-semibold">{{ $nama }}</span></p>
                <p>PIN: <span class="font-semibold">{{ $pin }}</span></p>
                <p>Tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
            </div>

            <div class="header-center">
                <img src="{{ asset('asset/img/logo.png') }}" alt="Logo">
                <h2>Net Digital Group</h2>
            </div>
        </div>


        <!-- Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Jumlah Hari Masuk</td>
                    <td class="text-right">{{ $gajiMasuk / 100000 }} hari</td>
                </tr>
                <tr>
                    <td>Jumlah Hari Lembur</td>
                    <td class="text-right">{{ $gajiLembur / 20000 }} jam</td>
                </tr>
                <tr>
                    <td>Keterlambatan ({{ $terlambatCount }} kali)</td>
                    <td class="text-right">-{{ number_format($potongan, 0, ',', '.') }} IDR</td>
                </tr>
                <tr class="total">
                    <td>Total Gaji</td>
                    <td class="text-right">{{ number_format($totalGaji, 0, ',', '.') }} IDR</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih atas kerja keras Anda!</p>
        </div>

        <!-- Print Button -->
        <div class="text-center no-print">
            <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded mt-6">Cetak Slip
                Gaji</button>
        </div>
    </div>
</body>

</html>
