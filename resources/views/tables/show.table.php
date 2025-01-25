<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Data</title>
</head>

<body>
    <h4>Table: {{ $table }}</h4>
    <h2>Columns:</h2>
    <ul>
        @foreach ($columns as $column)
        <li>{{ $column }}</li>
        @endforeach
    </ul>
    <h2>Data:</h2>
    <table border="1">
        <thead>
            <tr>
                @foreach ($columns as $column)
                <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr>
                @foreach ($columns as $column)
                <td>{{ $row->$column }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>