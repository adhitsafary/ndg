<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Download Log Absensi</title>
    <style>
        body {
            background-color: #caffcb;
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>

<body>

    <h3>Download Log Data</h3>

    <form action="{{ route('x100c.downloadLog') }}" method="GET">
        IP Address: <input type="text" name="ip" value="{{ old('ip', $IP) }}" size="15"><br>
        Comm Key: <input type="text" name="key" value="{{ old('key', $Key) }}" size="5"><br><br>
        <input type="submit" value="Download">
    </form>

    <br>

    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (isset($data))
        <h4>Hasil Download Log Absensi</h4>

        <table>
            <tr>
                <th>UserID</th>
                <th>Tanggal & Jam</th>
                <th>Verifikasi</th>
                <th>Status</th>
            </tr>

            @foreach ($data as $item)
                <tr>
                    <td>{{ htmlspecialchars($item['PIN']) }}</td>
                    <td>{{ htmlspecialchars($item['DateTime']) }}</td>
                    <td>{{ htmlspecialchars($item['Verified']) }}</td>
                    <td>{{ htmlspecialchars($item['Status']) }}</td>
                </tr>
            @endforeach
        </table>
    @endif

</body>

</html>
