@extends($layout)

@section('konten')
    <div class="ml-5 mr-5 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4>Kirim Pesan BOT Ucapan Lebaran</h4>
            </div>
            <div class="card-body">

                @if (session('status'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert"
                        style="background-color: #009b08; color: #1dff1d; border-color: #ffeeba;">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('ucapan_id2025.create') }}" method="GET" class="mb-3">
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
                                    @php
                                        $formattedValue = str_pad($i, 2, '0', STR_PAD_LEFT);
                                    @endphp
                                    <option value="{{ $formattedValue }}"
                                        {{ request('tgl_tagih_plg') == $formattedValue ? 'selected' : '' }}>
                                        {{ $formattedValue }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <button type="submit" class="btn btn-success w-100">Filter</button>
                        </div>
                    </div>
                </form>

                <form action="{{ route('ucapan_id2025.store') }}" method="POST">
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
                        Jumlah yang dipilih : 0
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
            let jml_pilih = select.selectedOptions.length;
            let message = "";

            if (jml_pilih > 0) {
                for (let option of select.selectedOptions) {
                    let nama = option.getAttribute('data-nama');
                    message += "🤖 *Bot Otomatis*\n\n";
                    message += "📢 *Selamat Hari Raya Idul Fitri 1446 H* 🌙✨\n\n";
                    message += `Pelanggan Setia,\nBapak/Ibu *${nama}*,\n\n`;
                    message += "السلام عليكم ورحمة اللّٰه وبركاته\n\n";
                    message +=
                        "Menjelang berakhirnya Bulan Suci Ramadhan, dan dengan segala kerendahan hati, kami memohon maaf atas segala salah dan khilaf.\n\n";
                    message += "*SELAMAT HARI RAYA IDUL FITRI 1 Syawal 1446 Hijriah*\n\n";
                    message += "‎تَقَبَّلَ اللَّهُ مِنَّا وَمِنْكُمْ صِيَامَنَا وَصِيَامَكُمْ تَقَبَّلْ يَا كَرِيْمَ\n\n";
                    message += "_Taqabbalallohu Minna wa Minkum shiyaamanaa washiyaamakum taqabbal yaa Kariim_\n\n";
                    message +=
                        "Kami segenap tim *Net Digital Group* mengucapkan selamat hari raya Idul Fitri, mohon maaf lahir dan batin.\n\n";
                    message +=
                        "Semoga Allah SWT mengampuni dosa kita, menerima amal ibadah dan puasa kita, serta dipertemukan kembali dengan Ramadhan yang akan datang dalam keadaan sehat.\n\n";
                    message += "*Aamiin Yaa Robbal 'Alamiin* 🤲\n\n";
                    message += "‎وَالسَّلاَمُ عَلَيْكُمْ وَرَحْمَةُ اللهِ وَبَرَكَاتُهُ\n\n";
                    message += "📞 *Kontak Kami:*\n";
                    message += "📌 *CS*   : 0857-9392-0206 (*Agisna* 🧕🏻)\n";
                    message += "📌 *Admin* : 0857-2222-0169 (*Gilang* 👳🏻‍♂️)\n\n";
                    message += "🔹 *Powered by netdigitalgroup.com* 🔹\n\n";
                }
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
