<!DOCTYPE html>
<html>

<head>
    <title>Data Pembayaran</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
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
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h4>Data Pembayaran</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>Tanggal Tagih</th>
                <th>Tanggal Pembayaran</th>
                <th>Jumlah Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayaran as $no => $item)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $item->nama_plg }}</td>
                    <td>{{ $item->alamat_plg }}</td>
                    <td>{{ $item->tgl_tagih_plg }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>{{ number_format($item->jumlah_pembayaran, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total Pembayaran</strong></td>
                <td><strong>{{ number_format($totalPembayaran, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
