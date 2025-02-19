@extends($layout)

@section('konten')
    <div class="ml-5 mr-5 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4>Kirim Pesan Pelanggan OOF WhatsApp</h4>
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert"
                        style="background-color: #009b08; color: #1dff1d; border-color: #ffeeba;">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('rayuan.create') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input type="text" name="search" placeholder="Nama Pelanggan"
                                class="form-control border-primary" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" name="alamat_plg" placeholder="Alamat" class="form-control border-primary"
                                value="{{ request('alamat_plg') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <select name="tgl_tagih_plg" id="tgl_tagih_plg" class="form-control border-primary"
                                onchange="this.form.submit();">
                                <option value="">Tanggal Tagih</option>
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}"
                                        {{ request('tgl_tagih_plg') == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <button type="submit" class="btn btn-success w-100">Filter</button>
                        </div>
                    </div>
                </form>

                <form action="{{ route('plg_off.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="token_id" class="form-label">Pilih Nomer</label>
                        <select name="token_id" id="token_id" class="form-control form-control-lg border-primary" required>
                            <option value="">-- Pilih Nomer --</option>
                            @foreach ($botTokens as $token)
                                <option value="{{ $token->id }}">{{ $token->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <select name="target[]" id="target" class="form-control form-control-lg border-primary" multiple
                        style="height: 300px;" onchange="updateMessage()">
                        @foreach ($pelanggan as $index => $item)
                            <option value="{{ $item->no_telepon_plg }}"
                                data-tgl_tagih="{{ \Carbon\Carbon::now()->setDay($item->tgl_tagih_plg)->format('d F Y') }}"
                                data-nama="{{ $item->nama_plg }}" data-paket="{{ $item->paket_plg }}">
                                {{ $index + 1 }}. {{ $item->nama_plg }} - {{ $item->alamat_plg }} -
                                {{ $item->no_telepon_plg }}
                            </option>
                        @endforeach
                    </select>


                    <!-- Tambahkan elemen untuk menampilkan jumlah pilihan -->
                    <div id="count-display" class="mb-3 text-danger">
                        Jumlah yang dipilih: 0
                    </div>

                    <div class="form-group mb-3">
                        <label for="message" class="form-label">Pesan:</label>
                        <textarea name="message" id="message" class="form-control border-primary" rows="10" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3 w-100">Kirim Pesan</button>
                </form>
            </div>
            <br>
        </div>
    </div>

    <script>
        function updateMessage() {
            let select = document.getElementById('target');
            let message = '';
            let count = select.selectedOptions.length;

            for (let option of select.selectedOptions) {
                let tglTagih = option.getAttribute('data-tgl_tagih');
                let nama = option.getAttribute('data-nama');
                let paket = option.getAttribute('data-paket');

                message += `*Spesial untuk Anda! Diskon Reaktivasi WiFi NET| NET DIGITAL 🚀📡*\n\n`;
                message += `**Halo ${nama},**\n\n`;
                message +=
                    `Kami mencatat bahwa layanan WiFi Anda saat ini tidak aktif. Kami memahami bahwa ada berbagai alasan yang mungkin menyebabkan Anda berhenti berlangganan. Namun, apakah Anda ingin kembali merasakan kenyamanan dengan koneksi internet yang stabil dan tanpa batas? 🏡📶\n\n`;
                message +=
                    `Kami punya **penawaran spesial hanya untuk pelanggan setia seperti Anda**:\n\n`;
                message += `🎉 *DISKON REAKTIVASI hingga [XX]%!*\n`;
                message += `📶 *Internet lebih stabil & cepat tanpa batasan kuota!*\n`;
                message += `💡 *Bebas biaya pemasangan ulang!* (S&K berlaku)\n`;
                message += `⚡ *Layanan prioritas untuk pelanggan lama!*\n\n`;
                message +=
                    `Jangan sampai ketinggalan promo spesial ini! **Diskon hanya berlaku hingga [tanggal berakhirnya promo]**.\n\n`;
                message +=
                    `Segera aktifkan kembali layanan WiFi Anda dengan menghubungi kami dengan cara membalas pesan ini. Kami siap menyambungkan kembali internet terbaik untuk rumah Anda! 🚀📡\n\n`;
                message += `**Koneksi lancar, harga hemat, hidup lebih nyaman!** 😍\n\n`;
                message += `*Salam, NET| NET DIGITAL*\n`;
                message += `📞 *Admin + CS* : 0857-9392-0206 (Agisna 🧕🏻)\n`;
                message += `📞 *Marketing* : 0857-2222-0169 (Gilang 👳🏻‍♂️)\n`;

            }

            document.getElementById('message').value = message;

            // Perbarui tampilan jumlah yang dipilih
            document.getElementById('count-display').innerText = `Jumlah yang dipilih: ${count}`;
        }
    </script>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            const tokenSelect = document.getElementById('token_id');
            if (!tokenSelect.value) {
                event.preventDefault(); // Mencegah pengiriman form
                alert('Silakan pilih token terlebih dahulu!');
                tokenSelect.focus(); // Memfokuskan kembali ke dropdown
            }
        });
    </script>
@endsection
