@extends($layout)

@section('konten')
    <div class="container">
        <h2>Dashboard Data</h2>

        <!-- Table Users -->
        <h4>Users</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $groupedUsers = $user->groupBy('name'); @endphp
                @foreach ($groupedUsers as $name => $users)
                    <tr>
                        <td>{{ $name }}</td>
                        <td>{{ $users->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Table Absensi -->
        <h4>Absensi</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $groupedAbsensi = $absensi->groupBy('nama'); @endphp
                @foreach ($groupedAbsensi as $nama => $items)
                    <tr>
                        <td>{{ $nama }}</td>
                        <td>{{ $items->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Table Pelanggan vs Pembayaran -->
        <h4>Pelanggan & Pembayaran</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal Tagih</th>
                    <th>Total Pelanggan</th>
                    <th>Total Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pelanggan->groupBy('tgl_tagih_plg') as $tgl => $items)
                    <tr>
                        <td>{{ $tgl }}</td>
                        <td>{{ $items->count() }}</td>
                        <td>{{ $pembayaran->where('tgl_tagih_plg', $tgl)->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Table Perbaikan -->
        <h4>Perbaikan (Success)</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Admin</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $groupedPerbaikan = $perbaikan->where('status', 'Success')->groupBy('admin'); @endphp
                @foreach ($groupedPerbaikan as $admin => $items)
                    <tr>
                        <td>{{ $admin }}</td>
                        <td>{{ $items->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Table PSB (Open) -->
        <h4>Pemasangan (Open)</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Admin</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $groupedPSB = $psb->where('status', 'open')->groupBy('admin'); @endphp
                @foreach ($groupedPSB as $admin => $items)
                    <tr>
                        <td>{{ $admin }}</td>
                        <td>{{ $items->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
