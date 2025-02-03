<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #absensiChart {
            width: 100% !important;
            max-width: 400px;
            height: 400px;
            max-height: 400px;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6 text-center">Detail Absensi {{ $nama }} 
        </h1>

        <!-- Tabel data absensi -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <table class="min-w-full table-auto border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">No</th>
                        <th class="border border-gray-300 px-4 py-2">Waktu</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $row)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $row->waktu }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $row->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Keterangan Absensi -->
        <div class="bg-white shadow-md rounded-lg p-6 mt-6">
            <h2 class="text-xl font-semibold mb-4">Keterangan Absensi</h2>
            <ul class="list-disc pl-6">
                <li><strong>Masuk:</strong> {{ $data->where('status', 'Masuk')->count() }} kali</li>
                <li><strong>Pulang:</strong> {{ $data->where('status', 'Pulang')->count() }} kali</li>
                <li><strong>Masuk Lembur:</strong> {{ $data->where('status', 'Masuk Lembur')->count() }} kali</li>
                <li><strong>Keluar Lembur:</strong> {{ $data->where('status', 'Keluar Lembur')->count() }} kali</li>
                <li><strong>Terlambat:</strong> {{ $terlambatCount }} kali</li>
            </ul>
        </div>

        <!-- Grafik Absensi -->
        <div class="bg-white shadow-md rounded-lg p-6 mt-6">
            <h2 class="text-xl font-semibold mb-4">Grafik Absensi</h2>
            <canvas id="absensiChart"></canvas>
        </div>

    </div>

    <script>
        // Ambil data dari PHP
        var masuk = {{ $data->where('status', 'Masuk')->count() }};
        var pulang = {{ $data->where('status', 'Pulang')->count() }};
        var masukLembur = {{ $data->where('status', 'Masuk Lembur')->count() }};
        var keluarLembur = {{ $data->where('status', 'Keluar Lembur')->count() }};

        // Buat chart
        var ctx = document.getElementById('absensiChart').getContext('2d');
        var absensiChart = new Chart(ctx, {
            type: 'pie', // Menggunakan jenis pie chart
            data: {
                labels: ['Masuk', 'Pulang', 'Masuk Lembur', 'Keluar Lembur'],
                datasets: [{
                    data: [masuk, pulang, masukLembur, keluarLembur],
                    backgroundColor: ['#4CAF50', '#FF6347', '#FFD700', '#4682B4'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Allow flexible sizing
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' kali';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
