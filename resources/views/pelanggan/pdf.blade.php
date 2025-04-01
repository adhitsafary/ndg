<!DOCTYPE html>
<html>

<head>
    <title>Data Pelanggan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h4 {
            background-color: #004299;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #004299;
            color: white;
        }

        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>Paket</th>
                <th>Harga</th>
                <th>Tanggal Tagih</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_pelanggan = count($pelanggan);
                $total_harga = 0;
            @endphp

            @foreach ($pelanggan as $no => $item)
                @php
                    $total_harga += $item->harga_paket;
                @endphp
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $item->nama_plg }}</td>
                    <td>{{ $item->alamat_plg }}</td>
                    <td>{{ $item->paket_plg }}</td>
                    <td>{{ number_format($item->harga_paket, 0, ',', '.') }}</td>
                    <td>{{ $item->tgl_tagih_plg }}</td>
                    <td>{{ $item->status_pembayaran }}</td>
                </tr>
            @endforeach

            <!-- Baris Total -->
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">Total Pelanggan:</td>
                <td colspan="2">{{ $total_pelanggan }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">Total Harga:</td>
                <td colspan="2">Rp {{ number_format($total_harga, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

</body>

</html>
