@extends($layout)

@section('konten')
    <div class="ml-5 mr-5">
        @if (session('error'))
            <div class="alert alert-danger" style="background: #a72828; color: white; border: 1px solid #ff0000;">
                {{ session('error') }}
            </div>
        @endif

        @if (session('alert'))
            <div class="alert  alert-dismissible fade show" role="alert"
                style="background: #a72828; color: white; border: 1px solid #ff0000;">
                {{ session('alert') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-dismissible fade show" role="alert"
                style="background: #28a745; color: white; border: 1px solid #218838;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Modal Loading -->
        <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden"></span>
                        </div>
                        <p class="mt-3">Sedang Memproses Data...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Sukses -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="successModalLabel">
                            <span class="me-2">✅</span> Berhasil!
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <h3 class="text-success">✔</h3>
                        <p id="successMessage" class="mt-2"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Error -->
        <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <span class="me-2">❌</span> Gagal!
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <h3 class="text-danger">✖</h3>
                        <p id="errorMessage" class="mt-2"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Form Pencarian -->

        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Form Pencarian -->
            <form action="{{ route('perbaikan.index') }}" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" name="cari" class="form-control" placeholder="Cari ID atau Nama Pelanggan..."
                        value="{{ request('cari') }}">
                    <button type="submit" class="btn btn-primary">🔍 Cari</button>
                </div>
            </form>

            <!-- Tombol Buat Perbaikan Baru -->
            <a href="{{ route('perbaikan.create') }}" class="btn btn-sm btn-primary">➕ Buat Perbaikan Baru</a>
        </div>

        <!-- Card Perbaikan -->
        <div class="row c">

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
                                    class="d-inline-block"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan perbaikan ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Selesaikan</button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
                @if (($no + 1) % 4 == 0)
                    <!-- Ubah 3 menjadi 4 -->
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
