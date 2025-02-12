<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi NDG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6 text-center">Data Absensi Net Digitalgroup</h1>

        <!-- Dropdown filter nama -->
        <div class="flex justify-between items-center mb-6">
            <form action="{{ route('x100c.show') }}" method="GET" class="flex space-x-4">
                <select name="nama" class="border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Pilih Nama</option>
                    @foreach ($allNames as $nama)
                        <option value="{{ $nama }}">{{ $nama }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Tampilkan</button>
            </form>
            <button onclick="window.print()"
                class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Cetak</button>
        </div>

        <!-- Tabel data absensi -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <table class="min-w-full table-auto border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">No</th>
                        <th class="border border-gray-300 px-4 py-2">Nama</th>
                        <th class="border border-gray-300 px-4 py-2">ID</th>
                        <th class="border border-gray-300 px-4 py-2">Waktu</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $row)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $row->nama }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $row->pin }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $row->waktu }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $row->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
