<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengeluaran Bulanan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Laporan Pengeluaran Bulanan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Deskripsi</th>
                <th>Harga Total</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($totalBulanan as $no => $item)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>{{ number_format($item->harga_total) }}</td>
                    <td>{{ $item->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold;">TOTAL</td>
                <td>{{ number_format($totalJumlah) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
