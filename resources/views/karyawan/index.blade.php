@extends('superadmin.layout_superadmin')

@section('konten')
    <div class="card m-5">

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

        
        <div class="row ml-0">
            <!-- Form Filter dan Pencarian -->
            <form action="{{ route('karyawan.index') }}" method="GET" class="form-inline mb-4 mr-3">
                <div class="input-group">
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Pencarian">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </div>
            </form>

            <a href="{{ route('karyawan.create') }}" class="mr-2">
                <button class="btn btn-primary">Tambah Karyawan</button>
            </a>
        </div>

        <table class="table table-bordered font-weight-bold" style="color: black;">
            <thead class="table table-primary font-weight-bold" style="color: black;">
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>KTP</th>
                    <th>Alamat</th>
                    <th>No Telpon</th>
                    <th>Posisi</th>
                    <th>Mulai Kerja</th>
                    <th>Gaji</th>
                    <th>Tanggal Gajihan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($karyawan as $no => $item)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>
                            <img src="{{ asset($item->foto) }}" alt="Foto Pengguna"
                                style="max-width: 60px; max-height: 60px; border-radius: 10%;">
                        </td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->ktp }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td>{{ $item->no_telepon }}</td>
                        <td>{{ $item->posisi }}</td>
                        <td>{{ $item->mulai_kerja }}</td>
                        <td>Rp {{ number_format($item->gaji, 0, ',', '.') }}</td>
                        <td>
                            @if (strtotime($item->tgl_gajihan))
                                {{ \Carbon\Carbon::parse($item->tgl_gajihan)->format('d-m-Y') }}
                            @else
                                <span class="text-danger">Tanggal tidak valid</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('karyawan.detail', $item->id) }}" class="btn btn-warning btn-sm">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">Tidak ada data ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

        // Tampilkan modal loading terlebih dahulu
        loadingModal.show();

        // Tunggu sebentar sebelum menampilkan modal sukses atau error
        setTimeout(function() {
            loadingModal.hide(); // Sembunyikan modal loading

            @if (session('success'))
                document.getElementById("successMessage").innerText = "✅ {{ session('success') }}";
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();

                // Tutup modal sukses setelah 3 detik
                setTimeout(function() {
                    successModal.hide();
                }, 3000);
            @endif

            @if (session('error'))
                document.getElementById("errorMessage").innerText = "❌ {{ session('error') }}";
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();

                // Tutup modal error setelah 3 detik
                setTimeout(function() {
                    errorModal.hide();
                }, 3000);
            @endif
        }, 1500); // Delay 1.5 detik untuk efek loading
    });
</script>
