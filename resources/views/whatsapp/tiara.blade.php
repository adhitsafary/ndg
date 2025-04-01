@extends($layout)

@section('konten')
<div class="ml-5 mr-5 mb-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Kirim Pesan WhatsApp Reminder</h4>
        </div>
        <div class="card-body">

            @if (session('status'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert"
                style="background-color: #009b08; color: #1dff1d; border-color: #ffeeba;">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form action="{{ route('tiara.create') }}" method="GET" class="mb-3">
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

            <form action="{{ route('tiara.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="token_id" class="form-label">Pilih Token:</label>
                    <select name="token_id" id="token_id" class="form-control form-control-lg border-primary" required>
                        <option value="">-- Pilih Token --</option>
                        @foreach ($botTokens as $token)
                        <option value="{{ $token->id }}">{{ $token->name }}</option>
                        @endforeach
                    </select>


                </div>

                <div class="form-group mb-3">
                    <label for="target" class="form-label">Pilih Target:</label>
                    <select name="target[]" id="target" class="form-control form-control-lg border-primary" multiple
                        style="height: 300px;" onchange="updateMessage()">
                        @foreach ($pelanggan as $item)
                        <option value="{{ $item->no_telepon_plg }}"
                            data-tgl_tagih="{{ \Carbon\Carbon::now()->setDay($item->tgl_tagih_plg)->format('d F Y') }}"
                            data-nama="{{ $item->nama_plg }}" data-paket="{{ $item->paket_plg }}">
                            {{ $item->nama_plg }} - {{ $item->no_telepon_plg }} - {{ $item->alamat_plg }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div id="jml_pilih" class="mb-3 text-danger">
                    Jumlah yang di pilih : 0
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
        let message = "*Pelanggan Tiara Net Yth👋👋* \n\n";
        let jml_pilih = select.selectedOptions.length;

        for (let option of select.selectedOptions) {
            let nama = option.getAttribute('data-nama');
            let alamat = option.getAttribute('data-alamat');
            let aktivasi = option.getAttribute('data-aktivasi');
            let hargaPaket = option.getAttribute('data-harga_paket');

            message += `Halo Bapak/Ibu *${nama}* - *${alamat}*, semoga hari Anda menyenangkan. 😊\n\n`;
            message += "Kami ingin mengingatkan bahwa pembayaran pemasangan WiFi Anda telah mencapai batas waktu yang disepakati, yaitu *1 minggu setelah pemasangan*.\n\n";
            message += `🔹 *Nama Pelanggan:* ${nama}\n`;
            message += `🔹 *Tanggal Aktivasi:* ${aktivasi}\n`;
            message += `🔹 *Biaya Pemasangan:* Rp. ${new Intl.NumberFormat('id-ID').format(hargaPaket)}\n`;
            message += "🔹 *Status:* Belum Dibayar\n\n";
            message += "💳 *Metode Bayar:*\n";
            message += "✅ *Via transfer:* rek BCA : 3770198576 a.n Ruslandi\n";
            message += "✅ *Pick-Up/Penjemputan* oleh petugas penagihan\n\n";
            message += "Mohon segera melakukan pembayaran agar tidak terjadi *pemutusan layanan internet* Anda. Jika sudah melakukan pembayaran, mohon konfirmasi kepada kami.\n\n";
            message += "Terima kasih atas kerja sama dan kepercayaan Anda menggunakan layanan kami. Jika ada kendala atau pertanyaan, jangan ragu untuk menghubungi kami. 😊🙏\n\n";
            message += "📞 *Admin* : 0857-9392-0206 (*Agisna* 🧕🏻)\n\n";
            message += "*TIARANET - Dari & Untuk Warga Tiara*\n\n";
        }

        document.getElementById('message').value = message;
        document.getElementById('jml_pilih').innerText = `Jumlah yang dipilih: ${jml_pilih}`;
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
