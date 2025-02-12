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
                        @php
                            $sn_modem = old('sn_modem', ''); // Ambil nilai lama jika ada
                            $sn = preg_match('/SN:([A-Za-z0-9]+)/', $sn_modem, $matches)
                                ? $matches[1]
                                : (preg_match('/&sn=([A-Za-z0-9]+)/', $sn_modem, $matches)
                                    ? $matches[1]
                                    : '');
                        @endphp
                        <input type="text" class="form-control" id="sn_modem" name="sn_modem" value="{{ $sn }}" required>
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

        <!-- Include jsQR library -->
        <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.min.js"></script>

        <script>
            const startScanner = document.getElementById('startScanner');
            const videoContainer = document.getElementById('video-container');
            const scannerVideo = document.getElementById('scanner');
            const snModemInput = document.getElementById('sn_modem');

            let videoStream;

            // Fungsi untuk memulai kamera menggunakan WebRTC API
            async function startCamera() {
                try {
                    // Minta izin akses kamera
                    videoStream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: "environment"
                        } // Gunakan kamera belakang
                    });

                    // Masukkan stream ke elemen video
                    scannerVideo.srcObject = videoStream;
                    videoContainer.style.display = 'block';

                    // Tunggu hingga video siap, lalu mulai membaca QR Code
                    scannerVideo.addEventListener('loadedmetadata', scanQRCode);
                } catch (error) {
                    console.error("Gagal mengakses kamera: ", error);
                    alert("Gagal mengakses kamera. Periksa pengaturan browser Anda.");
                }
            }

            // Fungsi untuk memindai QR Code menggunakan jsQR
            function scanQRCode() {
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');

                // Update dimensi canvas sesuai video
                canvas.width = scannerVideo.videoWidth;
                canvas.height = scannerVideo.videoHeight;

                const interval = setInterval(() => {
                    context.drawImage(scannerVideo, 0, 0, canvas.width, canvas.height);
                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    const qrCodeData = jsQR(imageData.data, canvas.width, canvas.height);

                    if (qrCodeData) {
                        console.log("QR Code Terdeteksi: ", qrCodeData.data);
                        snModemInput.value = qrCodeData.data; // Masukkan hasil scan ke input SN Modem

                        // Hentikan kamera setelah QR code terdeteksi
                        clearInterval(interval);
                        stopCamera();
                    }
                }, 100); // Scan setiap 100ms
            }

            // Fungsi untuk menghentikan kamera
            function stopCamera() {
                if (videoStream) {
                    videoStream.getTracks().forEach(track => track.stop());
                }
                videoContainer.style.display = 'none';
            }

            // Event listener untuk tombol startScanner
            startScanner.addEventListener('click', startCamera);
        </script>
    @endsection
