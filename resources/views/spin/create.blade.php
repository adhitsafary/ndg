
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pilihan Spin</title>
</head>

<body>
    <h2>Tambah Pilihan Barang</h2>
    <form action="{{ route('spin.store') }}" method="POST">
        @csrf
        <label for="name">Nama Barang:</label>
        <input type="text" name="name" id="name" placeholder="Masukkan nama barang" required>
        <label for="chance">Bobot Peluang:</label>
        <input type="number" name="chance" id="chance" placeholder="Masukkan bobot peluang" required>
        <button type="submit">Tambah Pilihan</button>
    </form>
    <a href="{{ route('spin.index') }}">Kembali ke Spin Wheel</a>
</body>

</html>
