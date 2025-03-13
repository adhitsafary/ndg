@extends($layout)

@section('konten')
    <div class="card ml-5 mr-5">
        <h2>Dashboard GA</h2>

        <div class="row">

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-light">Absensi</div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absensi->groupBy('nama') as $nama => $items)
                                    <tr>
                                        <td>{{ $nama }}</td>
                                        <td>{{ $items->count() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-light">Perbaikan (Success)</div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($perbaikan->where('status', 'Success')->groupBy('admin') as $admin => $items)
                                    <tr>
                                        <td>{{ $admin }}</td>
                                        <td>{{ $items->count() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-light">Pemasangan (Open)</div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($psb->where('status', 'open')->groupBy('admin') as $admin => $items)
                                    <tr>
                                        <td>{{ $admin }}</td>
                                        <td>{{ $items->count() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>

        <div class="row">



            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-light">Pelanggan & Pembayaran</div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm">
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
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
