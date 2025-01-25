@extends($layout)

@section('konten')
<div class="container mt-3">
    <h4 class="text-center">Data P3I</h4>

    <!-- Form Tambah Data -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Tambah Data</h5>
            <form action="{{ route('data-odp.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">Deskripsi:</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="tipe" class="form-label">Tipe:</label>
                    <select name="tipe" id="tipe" class="form-select" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="ODP">ODP</option>
                        <option value="ODC">ODC</option>
                        <option value="Crosure">Crosure</option>
                        <option value="Tiang">Tiang</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="foto" class="form-label">Foto:</label>
                    <input type="file" name="foto" id="foto" class="form-control" accept="image/*"
                        capture="environment" onchange="showPreview(event)">
                    <small class="form-text text-muted">Pilih gambar dari galeri atau gunakan kamera.</small>
                    <div id="preview" class="mt-2"></div>
                </div>

                <div class="mb-3">
                    <label for="maps" class="form-label">URL Maps (Opsional):</label>
                    <input type="text" name="maps" id="maps" class="form-control"
                        placeholder="Masukkan URL Maps jika diisi manual">
                </div>

                <input type="text" name="latitude" id="latitude" readonly>
                <input type="text" name="longitude" id="longitude" readonly>

                <button type="submit" class="btn btn-primary w-100">Tambah</button>
            </form>
        </div>
    </div>

    <!-- Daftar Data ODP -->
    <div class="container mt-4" style="width: 100%">
        <div class="row">
            @forelse ($data_odp as $no => $odp)
            <div class="col-12 col-md-6 col-lg-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            Data ODP
                            {{ $data_odp->total() - ($data_odp->currentPage() - 1) * $data_odp->perPage() - $loop->iteration + 1 }}
                        </h5>

                        <p class="card-text">
                            <strong>Nama:</strong> {{ $odp->nama }}<br>
                            <strong>Tipe:</strong> {{ $odp->tipe }}<br>
                            <strong>Maps:</strong>
                            @if ($odp->maps)
                            <a href="{{ $odp->maps }}" target="_blank">Buka Maps</a>
                            @else
                            <span class="text-muted">Tidak tersedia</span>
                            @endif
                        </p>
                        <div>
                            <strong>Foto:</strong><br>
                            <img src="{{ asset($odp->foto) }}" alt="Foto {{ $odp->nama }}"
                                class="img-thumbnail mt-2" style="max-width: 100%;">
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('data-odp.edit', $odp->id) }}"
                                class="btn btn-warning btn-sm me-2">Edit</a>
                            <form action="{{ route('data-odp.destroy', $odp->id) }}" method="POST"
                                class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p>Tidak ada data ODP ditemukan</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.getElementById('foto').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const maxSize = 8 * 1024 * 1024; // 8MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

        if (!file) {
            alert('Harap pilih file.');
            return;
        }

        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Hanya jpeg, png, jpg, dan gif yang diizinkan.');
            event.target.value = '';
            return;
        }

        if (file.size > maxSize) {
            alert('Ukuran file melebihi batas 8MB.');
            event.target.value = '';
            return;
        }
    });

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
            },
            function(error) {
                alert('Gagal mendapatkan lokasi: ' + error.message);
            }
        );
    } else {
        alert('Geolocation tidak didukung di browser ini.');
    }

    function showPreview(event) {
        const previewContainer = document.getElementById('preview');
        previewContainer.innerHTML = '';
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100%';
                img.classList.add('img-thumbnail');
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection