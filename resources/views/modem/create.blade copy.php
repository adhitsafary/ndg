@extends($layout)
@section('konten')
    <div class="card m-5">
        <div class="">
            <h4>Tambah Modem Baru</h4><br>

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

            <!-- Elemen untuk menampilkan kamera -->
            <div id="video-container" style="display: none;">
                <video id="scanner" autoplay muted playsinline></video>
            </div>
        </div>
    </div>

    <!-- Tambahkan CSS -->
    <style>
        #video-container {
            display: block;
            margin-top: 20px;
        }

        #scanner {
            width: 100%;
            height: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>

    <!-- Include QuaggaJS library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <script>
        const startScanner = document.getElementById('startScanner');
        const videoContainer = document.getElementById('video-container');
        const scannerVideo = document.getElementById('scanner');
        const snModemInput = document.getElementById('sn_modem');

        let videoStream;

        // Fungsi untuk memulai pemindaian barcode
        function startBarcodeScanner() {
            videoContainer.style.display = 'block';

            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: scannerVideo, // Elemen video untuk live stream
                    constraints: {
                        facingMode: "environment" // Gunakan kamera belakang
                    }
                },
                decoder: {
                    readers: [
                        "code_128_reader", // Format barcode Code128
                        "ean_reader", // Format barcode EAN
                        "ean_13_reader", // Format barcode EAN-13
                        "upc_reader" // Format barcode UPC
                    ]
                }
            }, function(err) {
                if (err) {
                    console.error("QuaggaJS error:", err);
                    alert("Gagal memulai scanner!");
                    return;
                }
                Quagga.start();
            });

            // Event ketika barcode terdeteksi
            Quagga.onDetected(function(data) {
                const barcode = data.codeResult.code;
                console.log("Kode Barcode:", barcode);
                snModemInput.value = barcode; // Masukkan hasil scan ke input SN Modem

                // Hentikan scanner setelah barcode terbaca
                Quagga.stop();
                videoContainer.style.display = 'none';
            });
        }

        // Event listener untuk tombol startScanner
        startScanner.addEventListener('click', startBarcodeScanner);
    </script>
@endsection
