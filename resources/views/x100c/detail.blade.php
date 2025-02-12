<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
    <div class="container mx-auto py-10 ">
        <h1 class="text-3xl font-bold mb-6 text-center">Detail Absensi {{ $nama }}</h1>

        <div class="ml-5">
            <!-- Tombol Print -->
            <button onclick="printTable()" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
                🖨️ Cetak Absensi
            </button>
        </div>

        <!-- Tabel Data Absensi -->
        <div class="bg-white shadow-md rounded-lg p-6 m-5">
            <table id="absensiTable" class="min-w-full table-auto border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">No</th>
                        <th class="border border-gray-300 px-4 py-2">Waktu</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                        <th class="border border-gray-300 px-4 py-2">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $row)
                        <tr id="row-{{ $row->id }}">
                            <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $row->waktu }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $row->status }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <button onclick="hapusAbsensi({{ $row->id }})"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">
                                    Hapus
                                </button>
                            </td>
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
        <!--  <div class="bg-white shadow-md rounded-lg p-6 mt-6 m-5">
            <h2 class="text-xl font-semibold mb-4">📋 Keterangan Absensi</h2>
            <ul class="list-disc pl-6">
                <li><strong>✅ Masuk:</strong> {{ $data->where('status', 'Masuk')->count() }} kali</li>
                <li><strong>🏠 Pulang:</strong> {{ $data->where('status', 'Pulang')->count() }} kali</li>
                <li><strong>🌙 Masuk Lembur:</strong> {{ $data->where('status', 'Masuk Lembur')->count() }} kali</li>
                <li><strong>🌙 Keluar Lembur:</strong> {{ $data->where('status', 'Keluar Lembur')->count() }} kali</li>
                <li><strong>⏳ Terlambat:</strong> {{ $terlambatCount }} kali</li>
            </ul>
        </div> -->

        <div class="bg-white shadow-md rounded-lg p-6 mt-6 m-5">
            <h2 class="text-xl font-semibold mb-4">📋 Keterangan Absensi</h2>
            <table class="min-w-full table-auto border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">Jenis Absensi</th>
                        <th class="border border-gray-300 px-4 py-2">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">✅ Masuk</td>
                        <td class="border border-gray-300 px-4 py-2"> {{ $data->where('status', 'Masuk')->count() }}
                            kali</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">🏠 Pulang</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $data->where('status', 'Pulang')->count() }}
                            kali</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">🌙 Masuk Lembur</td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $data->where('status', 'Masuk Lembur')->count() }} kali</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">🌙 Keluar Lembur</td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $data->where('status', 'Keluar Lembur')->count() }} kali</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">⏳ Terlambat</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $terlambatCount }} kali</td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Keterangan Absensi -->
        <div class="bg-white shadow-md rounded-lg p-6 mt-6 m-5">
            <h2 class="text-xl font-semibold mb-4">📋 Absensi</h2>
            <table class="min-w-full table-auto border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">Jenis Absensi</th>
                        <th class="border border-gray-300 px-4 py-2">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data Hadir -->
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">✅ Hadir (Masuk & Pulang)</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $hadirCount }} kali</td>
                    </tr>

                    <!-- Data Lembur -->
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">🌙 Total Lembur</td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $totalLemburMenit }} Menit / {{ $totalLemburJam }} Jam
                        </td>
                    </tr>

                    <!-- Terlambat -->
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">⏳ Terlambat</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $terlambatCount }} kali</td>
                    </tr>
                </tbody>
            </table>
        </div>



        <!-- JavaScript untuk Print -->
        <script>
            function printTable() {
                var printWindow = window.open('', '_blank');
                printWindow.document.write('<html><head><title>Cetak Absensi</title>');
                printWindow.document.write('<style>');
                printWindow.document.write('body { font-family: Arial, sans-serif; text-align: center; }');
                printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
                printWindow.document.write('th, td { border: 1px solid black; padding: 8px; text-align: center; }');
                printWindow.document.write('th { background-color: #f2f2f2; }');
                printWindow.document.write('</style></head><body>');

                printWindow.document.write('<h2>Data Absensi</h2>');
                printWindow.document.write(document.getElementById('absensiTable').outerHTML);


                // Menambahkan judul
                printWindow.document.write('<h4 style="text-align:center;">📋 Rekapitulasi Absensi</h4>');

                // Membuat tabel absensi
                printWindow.document.write('<table>');
                printWindow.document.write('<tr><th>Jenis Absensi</th><th>Jumlah</th></tr>');

                // ✅ Data Hadir
                printWindow.document.write(
                    '<tr><td>✅ Hadir (Masuk & Pulang)</td><td>{{ $hadirCount }} kali</td></tr>'
                );

                // 🌙 Data Lembur
                printWindow.document.write(
                    '<tr><td>🌙 Total Lembur</td><td>{{ $totalLemburMenit }} Menit / {{ $totalLemburJam }} Jam</td></tr>'
                );

                // ⏳ Data Terlambat
                printWindow.document.write(
                    '<tr><td>⏳ Terlambat</td><td>{{ $terlambatCount }} kali</td></tr>'
                );


                printWindow.document.write('</tbody>');
                printWindow.document.write('</table>');


                printWindow.document.write('</body></html>');

                printWindow.document.close();
                printWindow.print();
            }
        </script>








        <!-- Grafik Absensi -->
        <div class="bg-white shadow-md rounded-lg p-6 mt-6  m-5">
            <h2 class="text-xl font-semibold mb-4">Grafik Absensi</h2>
            <canvas id="absensiChart"></canvas>
        </div>
    </div>

    <script>
        function hapusAbsensi(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                axios.delete(`/x100c/detail/delete/${id}`)
                    .then(response => {
                        document.getElementById(`row-${id}`).remove();
                        alert('Data berhasil dihapus');
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Gagal menghapus data');
                    });
            }
        }

        var masuk = {{ $data->where('status', 'Masuk')->count() }};
        var pulang = {{ $data->where('status', 'Pulang')->count() }};
        var masukLembur = {{ $data->where('status', 'Masuk Lembur')->count() }};
        var keluarLembur = {{ $data->where('status', 'Keluar Lembur')->count() }};

        var ctx = document.getElementById('absensiChart').getContext('2d');
        var absensiChart = new Chart(ctx, {
            type: 'pie',
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
                maintainAspectRatio: false,
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
