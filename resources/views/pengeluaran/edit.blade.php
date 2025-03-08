@extends($layout)

@section('konten')
    <div class="container mt-4">
        <h6 class="text-center text-black mt-3">Edit Data Pengeluaran: {{ $pengeluaran->deskripsi }}</h6>
        <form action="{{ route('pengeluaran.update', $pengeluaran->id) }}" method="POST">
            @csrf


            <!-- Input Deskripsi -->
            <label for="deskripsi" class="mt-2">Deskripsi</label>
            <input type="text" name="deskripsi" value="{{ $pengeluaran->deskripsi }}" class="form-control" required>

            <!-- Input Harga Satuan -->
            <label for="harga_satuan" class="mt-2">Harga Satuan</label>
            <input type="number" name="harga_satuan" id="harga_satuan" value="{{ $pengeluaran->harga_satuan }}"
                class="form-control" required oninput="hitungTotal()">

            <!-- Input Volume -->
            <label for="volume" class="mt-2">Volume</label>
            <input type="number" name="volume" id="volume" value="{{ $pengeluaran->volume }}" class="form-control"
                required oninput="hitungTotal()">

            <!-- Input Harga Total (otomatis dihitung) -->
            <label for="harga_total" class="mt-2">Harga Total</label>
            <input type="number" name="harga_total" id="harga_total" value="{{ $pengeluaran->harga_total }}"
                class="form-control" required readonly>

            <!-- Input Keterangan -->
            <label for="keterangan" class="mt-2">Keterangan</label>
            <input type="text" name="keterangan" value="{{ $pengeluaran->keterangan }}" class="form-control" required>

            <!-- Pilihan Kategori -->
            <label for="kategori" class="mt-2">Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="">Kategori</option>
                <option value="Bensin" {{ $pengeluaran->kategori == 'Bensin' ? 'selected' : '' }}>Bensin</option>
                <option value="Modem" {{ $pengeluaran->kategori == 'Modem' ? 'selected' : '' }}>Modem</option>
                <option value="Pathcore" {{ $pengeluaran->kategori == 'Pathcore' ? 'selected' : '' }}>Pathcore</option>
                <option value="Kabel" {{ $pengeluaran->kategori == 'Kabel' ? 'selected' : '' }}>Kabel</option>
                <option value="Splitter" {{ $pengeluaran->kategori == 'Splitter' ? 'selected' : '' }}>Splitter</option>
                <option value="Listrik" {{ $pengeluaran->kategori == 'Listrik' ? 'selected' : '' }}>Listrik</option>
                <option value="Internet" {{ $pengeluaran->kategori == 'Internet' ? 'selected' : '' }}>Internet</option>
                <option value="Peralatan" {{ $pengeluaran->kategori == 'Peralatan' ? 'selected' : '' }}>Peralatan</option>
                <option value="Perlengkapan" {{ $pengeluaran->kategori == 'Perlengkapan' ? 'selected' : '' }}>Perlengkapan
                </option>
                <option value="Transport" {{ $pengeluaran->kategori == 'Transport' ? 'selected' : '' }}>Transport</option>
                <option value="Makan" {{ $pengeluaran->kategori == 'Makan' ? 'selected' : '' }}>Makan</option>
                <option value="Jasa Perbaikan" {{ $pengeluaran->kategori == 'Jasa Perbaikan' ? 'selected' : '' }}>Jasa
                    Perbaikan</option>
                <option value="Pemasangan" {{ $pengeluaran->kategori == 'Pemasangan' ? 'selected' : '' }}>Pemasangan
                </option>
                <option value="lain-lain" {{ $pengeluaran->kategori == 'lain-lain' ? 'selected' : '' }}>Lain-lain</option>
            </select>


            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-sm mt-3">Simpan</button>
        </form>
    </div>

    <script>
        function hitungTotal() {
            let hargaSatuan = document.getElementById('harga_satuan').value;
            let volume = document.getElementById('volume').value;
            let hargaTotal = hargaSatuan * volume;

            document.getElementById('harga_total').value = hargaTotal || 0;
        }
    </script>
@endsection
