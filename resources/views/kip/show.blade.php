@extends($layout)

@section('konten')
    <div class="container card p-4">
        <h2 class="mb-3">Detail Pekerja: {{ $pekerja['nama'] }}</h2>

        <!-- Menampilkan Foto Pekerja -->
     

        <!-- Chart Kinerja -->
        <canvas class="card" id="kinerjaChart" width="300" height="300"></canvas>

        <div class="mt-3 card p-3">
            <h5>Ringkasan Kinerja</h5>
            <p>
                <strong>Jumlah Kehadiran:</strong> {{ $pekerja['hadir'] }} hari |
                <strong>Jumlah Pekerjaan:</strong> {{ $pekerja['pekerjaan'] }} tugas selesai
            </p>
            <p><strong>Produktivitas:</strong>
                {{ $pekerja['hadir'] > 0 ? round(($pekerja['pekerjaan'] / $pekerja['hadir']) * 100, 2) : 0 }}%
            </p>
        </div>

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            var ctx = document.getElementById('kinerjaChart').getContext('2d');

            var kinerjaChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Kehadiran', 'Pekerjaan'],
                    datasets: [{
                        data: [{{ $pekerja['hadir'] }}, {{ $pekerja['pekerjaan'] }}],
                        backgroundColor: ['#36A2EB', '#FF6384']
                    }]
                },
                options: {
                    responsive: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        </script>

        <a href="{{ route('kip.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
@endsection
