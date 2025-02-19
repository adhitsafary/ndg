@extends($layout)

@section('konten')
    <div class="m-5">
        <div class="card-body pl-5 pr-5">
            <div class="mb-4">
                <!-- Form Filter dan Pencarian -->
                <div class="row mb-4">
                    <div class="col-md-9">
                        <form action="{{ route('perbaikan.index') }}" method="GET" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="search" id="search" class="form-control font-weight-bold"
                                    value="{{ request('search') }}" placeholder="Pencarian">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-danger">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3 text-right">
                        <a href="/perbaikan/create" class="btn btn-danger">Buat Perbaikan</a>
                        <a href="/rekap-teknisi" class="btn btn-danger">Rekap Bulanan Teknisi</a>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Card Perbaikan -->
            <div class="row">
                @forelse ($perbaikan as $no => $item)
                    <div class="col-md-3 mb-3"> <!-- Ubah col-md-4 menjadi col-md-3 -->
                        <div class="card">
                            <div class="card-header">
                                <h5>Tiket: {{ $item->kd_tiket }} - {{ $item->nama_plg }}</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><strong>Nama Pelanggan:</strong> {{ $item->nama_plg }}</p>
                                <p class="card-text"><strong>Alamat:</strong> {{ $item->alamat_plg }}</p>
                                <p class="card-text"><strong>No HP:</strong> {{ $item->no_telepon_plg }}</p>
                                <p class="card-text"><strong>Paket:</strong> {{ $item->paket_plg }}</p>
                                <p class="card-text"><strong>Odp:</strong> {{ $item->odp }}</p>
                                <p class="card-text"><strong>Maps:</strong> {{ $item->maps }}</p>
                                <p class="card-text"><strong>Teknisi:</strong> {{ $item->teknisi }}</p>
                                <p class="card-text"><strong>Gangguan:</strong> {{ $item->keterangan }}</p>
                                <p class="card-text"><strong>Keterangan:</strong> {{ $item->info }}</p>
                                <p class="card-text"><strong>Tanggal:</strong> {{ $item->created_at }}</p>
                                <p class="card-text"><strong>Status:</strong> {{ ucfirst($item->status) }}</p>
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ route('perbaikan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('perbaikan.destroy', $item->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                </form>
                                @if ($item->status == 'Proses')
                                    <form action="{{ route('perbaikan.selesai', $item->id) }}" method="POST"
                                        class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Selesai</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (($no + 1) % 4 == 0) <!-- Ubah 3 menjadi 4 -->
            </div>
            <div class="row"> <!-- Memulai baris baru setiap 4 item -->
                @endif
            @empty
                <p class="text-center">Tidak ada data ditemukan</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
