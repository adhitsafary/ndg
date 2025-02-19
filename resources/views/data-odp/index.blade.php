@extends($layout)

@section('konten')
    <div class="card m-5">
        <!-- Form Tambah Data -->
        <div class=" mb-4">
            <div class="card-body">
                <h4 class="text-center">Data P3I</h4>
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
                            <option value="Rumah">Rumah</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto:</label>
                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*"
                            capture="environment" onchange="showPreview(event)">
                        <small class="form-text text-muted">Pilih gambar dari galeri atau gunakan kamera.</small>
                        <div id="preview" class="mt-2">
                            <!-- Preview akan muncul di sini -->
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="maps" class="form-label">URL Maps (Opsional):</label>
                        <input type="text" name="maps" id="maps" class="form-control"
                            placeholder="Masukkan URL Maps jika diisi manual">
                    </div>
                    <label for="">Latitude</label>
                    <input type="text" name="latitude" id="latitude" readonly><br>
                    <label for="">Latitude</label>
                    <input type="text" name="longitude" id="longitude" readonly> <br> <br>

                    <button type="submit" class="btn btn-primary w-100">Tambah</button>
                </form>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Daftar Data ODP -->
        <div class="container mt-4" style="width: 100%">
            <div class="row">
                @forelse ($data_odp as $no => $odp)
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">
                                    Data
                                    {{ $data_odp->total() - ($data_odp->currentPage() - 1) * $data_odp->perPage() - $loop->iteration + 1 }}
                                </h6>
                                <p class="card-text">
                                    <strong>Nama: {{ $odp->nama }}</strong> <br>
                                    <strong>Tipe:</strong> {{ $odp->tipe }}<br>
                                    <strong> @if ($odp->maps)
                                            <strong> Maps : </strong>
                                            <a href="{{ $odp->maps }}" target="_blank"> <img
                                                    src="{{ asset('asset/img/icon/map.png') }}" height="40px"
                                                    class="m-0" alt=""> </a>
                                        @else
                                            <span class="text-muted">Tidak tersedia</span>
                                        @endif
                                    </strong>


                                </p>
                                <div>
                                    <strong>Foto:</strong><br>
                                    <a href="{{ asset($odp->foto) }}" target="_blank">
                                        <img src="{{ asset($odp->foto) }}" alt="Foto {{ $odp->nama }}"
                                            class="img-thumbnail mt-2" style="max-width: 100%;">
                                    </a>
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
            const maxSize = 1 * 1024 * 1024; // 1MB
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
                // Jika ukuran file lebih dari 1MB, perkecil gambar
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.src = e.target.result;

                    img.onload = function() {
                        // Membuat canvas untuk mengubah ukuran gambar
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');

                        // Tentukan ukuran baru (contohnya, mengubah lebar menjadi 1024px, tinggi disesuaikan dengan rasio)
                        const MAX_WIDTH = 1024;
                        const scaleRatio = MAX_WIDTH / img.width;
                        canvas.width = MAX_WIDTH;
                        canvas.height = img.height * scaleRatio;

                        // Gambar ulang gambar ke canvas dengan ukuran baru
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                        // Mengonversi canvas ke base64 dan membuat file baru
                        canvas.toBlob(function(blob) {
                            // Membuat file baru dengan ukuran yang lebih kecil
                            const newFile = new File([blob], file.name, {
                                type: file.type
                            });

                            // Tentukan file baru ke input file
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(newFile);
                            document.getElementById('foto').files = dataTransfer.files;

                            // Menampilkan preview gambar
                            showPreview(newFile);
                        }, file.type, 0.7); // 0.7 untuk mengatur kompresi (nilai antara 0 dan 1)
                    };
                };
                reader.readAsDataURL(file);
            } else {
                // Jika file sudah kecil (dibawah 1MB), langsung tampilkan preview
                showPreview(file);
            }
        });

        function showPreview(file) {
            const previewContainer = document.getElementById('preview');
            previewContainer.innerHTML = '';
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

        // Mendapatkan lokasi dan menampilkan di input latitude dan longitude
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Set latitude dan longitude pada inputan
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                },
                function(error) {
                    // Jika gagal, tampilkan alert
                    alert('Gagal mendapatkan lokasi: ' + error.message);
                }
            );
        } else {
            alert('Geolocation tidak didukung di browser ini.');
        }

        function showPreview(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            preview.innerHTML = ''; // Clear previous preview

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Pratinjau gambar';
                    img.style.maxWidth = '100px'; // Atur ukuran gambar
                    img.style.maxHeight = '100px'; // Atur ukuran gambar
                    img.className = 'img-thumbnail'; // Tambahkan gaya bootstrap jika diperlukan
                    preview.appendChild(img);
                };

                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
