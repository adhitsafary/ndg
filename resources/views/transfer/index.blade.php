@extends($layout)

@section('konten')
    <div>
        <div class="max-w-xl mx-auto p-6 bg-white rounded-xl shadow-md mt-10">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Transfer Saldo</h2>

            @if (session('success'))
                <div class="bg-green-100 p-3 rounded mb-4 text-green-700">
                    {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div class="bg-red-100 p-3 rounded mb-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('transfer.saldo') }}">
                @csrf

                {{-- Hidden receiver_id yang akan dikirim --}}
                <input type="hidden" name="receiver_id" id="receiver_id_input">

                {{-- Dropdown Penerima --}}
                <div class="mb-4">
                    <label for="receiver_select" class="block text-gray-700 font-semibold">Pilih Penerima</label>
                    <select id="receiver_select" class="w-full mt-1 border-gray-300 rounded shadow-sm">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} (Saldo:
                                {{ number_format($user->saldo) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Jumlah saldo --}}
                <div class="mb-4">
                    <label for="jumlah_saldo" class="block text-gray-700 font-semibold">Jumlah Saldo</label>
                    <input type="number" name="jumlah_saldo" id="jumlah_saldo"
                        class="w-full mt-1 border-gray-300 rounded shadow-sm" min="100" required>
                </div>

                {{-- QR Scanner --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold">Atau Scan QR Penerima</label>
                    <div id="reader" class="border border-dashed border-gray-300 p-4 rounded bg-gray-50 mt-2"></div>
                </div>

                <div class="mb-4 text-right">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script Scanner --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.onload = function() {
            const selectEl = document.getElementById('receiver_select');
            const inputHidden = document.getElementById('receiver_id_input');

            // Default: inputHidden diisi dari select saat page load
            inputHidden.value = selectEl.value;

            // Jika user ganti dropdown manual
            selectEl.addEventListener('change', function() {
                inputHidden.value = this.value;
            });

            // Mulai QR scanner
            const html5QrCode = new Html5Qrcode("reader");
            const config = {
                fps: 10,
                qrbox: 200
            };

            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    // Coba cari kamera belakang
                    let backCamera = devices.find(device =>
                        device.label.toLowerCase().includes('back') ||
                        device.label.toLowerCase().includes('environment')
                    );

                    // Jika tidak ketemu, fallback ke kamera pertama
                    const cameraId = backCamera ? backCamera.id : devices[0].id;

                    html5QrCode.start(
                        cameraId,
                        config,
                        qrCodeMessage => {
                            inputHidden.value = qrCodeMessage;
                            selectEl.disabled = true;
                            html5QrCode.stop();
                            alert("QR Code terdeteksi! ID: " + qrCodeMessage);
                        },
                        error => {
                            // Ignore scan errors
                        }
                    );
                }
            }).catch(err => {
                console.error("Gagal mengakses kamera:", err);
            });

        };
    </script>
@endsection
