@extends('layout_login')

@section('konten')
<div class="container mt-3">
    <h4 class="text-center">Data P3I</h4>
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
                    <input type="file" name="foto" id="foto" class="form-control" accept="image/*" capture="environment" onchange="showPreview(event)">
                    <small class="form-text text-muted">Pilih gambar dari galeri atau gunakan kamera.</small>
                    <div id="preview" class="mt-2"></div>
                </div>

                <div class="mb-3">
                    <label for="maps" class="form-label">URL Maps (Opsional):</label>
                    <input type="text" name="maps" id="maps" class="form-control" placeholder="Masukkan URL Maps jika diisi manual">
                </div>

                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <button type="submit" class="btn btn-primary w-100">Tambah</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Data ODP</h5>
            <table class="table table-bordered table-responsive text-center">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Foto</th>
                        <th>Maps</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_odp as $no => $odp)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $odp->nama }}</td>
                        <td>{{ $odp->tipe }}</td>
                        <td><img src="{{ asset($odp->foto) }}" alt="Foto {{ $odp->nama }}" width="100" class="img-thumbnail"></td>
                        <td>
                            @if ($odp->maps)
                            <a href="{{ $odp->maps }}" target="_blank" class="btn btn-sm btn-success">Lihat Maps</a>
                            @else
                            <span class="text-muted">Tidak tersedia</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('data-odp.edit', $odp->id) }}" class="btn btn-sm btn-warning mb-1">Edit</a>
                            <form action="{{ route('data-odp.destroy', $odp->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
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
        alert("Geolocation tidak didukung di browser ini.");
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