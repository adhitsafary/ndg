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



        <div class="card">
            <h4 class="text-left">Daftar Perbaikan</h4>

            <table class="table table-bordered table-striped mt-3">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>No Telepon</th>
                        <th>ODP</th>
                        <th>Gangguan</th>
                        <th>Teknisi</th>
                        <th>Status</th>
                        <th style="width:8cm">Aksi </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($perbaikans as $index => $perbaikan)
                        <tr>
                            <td>{{ $perbaikans->firstItem() + $index }}</td>
                            <td>{{ $perbaikan->nama_plg }}</td>
                            <td>{{ $perbaikan->no_telepon_plg }}</td>
                            <td>{{ $perbaikan->odp }}</td>
                            <td>{{ $perbaikan->keterangan }}</td>
                            <td>
                                @php
                                    $teknisiList = json_decode($perbaikan->teknisi, true);
                                @endphp
                                {{ is_array($teknisiList) ? implode(', ', $teknisiList) : $perbaikan->teknisi }}
                            </td>

                            <td>
                                @if ($perbaikan->status == 'Proses')
                                    <form action="{{ route('perbaikan.selesai', $perbaikan->id) }}" method="POST"
                                        class="d-inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan perbaikan ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Proses</button>
                                    </form>
                                @endif
                            </td>



                            <td>


                                <a href="{{ route('perbaikan.show', $perbaikan->id) }}"
                                    class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('inventory.returnForm', $perbaikan->id) }}"
                                    class="btn btn-info btn-sm">Return</a>
                                <a href="{{ route('perbaikan.edit', $perbaikan->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('perbaikan.destroy', $perbaikan->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf

                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <div class="d-flex justify-content-center mt-3 mb-5">
            {{ $perbaikans->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

            // Tampilkan modal loading terlebih dahulu
            loadingModal.show();

            // Tunggu sebentar sebelum menampilkan modal sukses/error atau alert
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
@endsection
