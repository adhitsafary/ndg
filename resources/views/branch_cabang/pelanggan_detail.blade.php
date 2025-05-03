@extends($layout)

@section('konten')
    <div class="m-4">
        <div class="card p-4 shadow-sm table-responsive">
            <h5 class="text-primary mb-4">Daftar Pelanggan - Kode Cabang: {{ $kode_cabang }}</h5>
            <a href="{{ route('cabang.index') }}" class="btn btn-secondary mb-3">← Kembali</a>
            <div>
                <form method="GET" class="mb-3 d-flex gap-2">
                    <input type="text" name="nama" class="form-control" placeholder="Cari Nama..."
                        value="{{ request('nama') }}">
                    <input type="text" name="paket" class="form-control" placeholder="Cari Paket..."
                        value="{{ request('paket') }}">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('cabang.pelanggan', $kode_cabang) }}"
                        class="btn btn-outline-secondary">Reset</a>
                </form>
            </div>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pelanggan</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Paket</th>
                        <th>No Telp</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pelanggan as $i => $plg)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $plg->id_plg }}</td>
                            <td>{{ $plg->nama_plg }}</td>
                            <td>{{ $plg->alamat_plg }}</td>
                            <td>{{ $plg->paket_plg }}</td>
                            <td>{{ $plg->no_telepon_plg }}</td>
                            <td>{{ $plg->keterangan_plg }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada pelanggan di cabang ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
