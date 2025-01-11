@extends($layout)
@section('konten')
    <div class="container">
        <h2>Tambah Modem Baru</h2> <br>

        <form action="{{ route('modem.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="sn_modem" class="form-label">SN Modem</label>
                <div class="d-flex w-100 justify-content-center align-items-center">
                    <input type="text" class="form-control" id="sn_modem" name="sn_modem" required>
                    <img src="{{ asset('asset/img/icon/camera.png') }}" alt="Scan Barcode" id="startScanner"
                        style="width: 60px; height: 60px; cursor: pointer; margin-left: 10px;">
                </div>
            </div>

            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input type="text" class="form-control" id="model" name="model" required>
            </div>
            <div class="mb-3">
                <label for="tgl_keluar" class="form-label">Tanggal Keluar</label>
                <input type="date" class="form-control" id="tgl_keluar" name="tgl_keluar">
            </div>
            <div class="mb-3">
                <label for="user" class="form-label">User</label>
                <input type="text" class="form-control" id="user" name="user">
            </div>
            <div class="mb-3">
                <label for="id_mikrotik" class="form-label">ID MikroTik</label>
                <input type="text" class="form-control" id="id_mikrotik" name="id_mikrotik">
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <input type="text" class="form-control" id="keterangan" name="keterangan">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>

        <!-- Elemen untuk menampilkan live stream kamera -->
        <div id="video-container" style="display:none;">
            <video id="scanner" width="100%" height="auto" style="border: 1px solid #ccc;" autoplay></video>
        </div>
    </div>

    <!-- Include QuaggaJS library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <script>
        // Event listener untuk tombol startScanner
        document.getElementById('startScanner').addEventListener('click', function() {
            // Menampilkan container video dan mulai scan
            document.getElementById('video-container').style.display = 'block';

            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#scanner'), // Menampilkan live stream di elemen video
                    constraints: {
                        facingMode: "environment" // Menggunakan kamera belakang
                    }
                },
                decoder: {
                    readers: ["ean_reader", "ean_13_reader", "upc_reader"]
                }
            }, function(err) {
                if (err) {
                    console.log(err);
                    return;
                }
                Quagga.start();
            });

            // Event ketika barcode terdeteksi
            Quagga.onDetected(function(data) {
                document.getElementById('sn_modem').value = data.codeResult.code; // Menampilkan hasil scan ke input
                Quagga.stop(); // Menghentikan scan setelah barcode terdeteksi
            });
        });
    </script>
@endsection
