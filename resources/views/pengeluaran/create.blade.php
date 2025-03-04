@extends($layout)

@section('konten')
    <div class="card m-5 p-4">
        <h6 class="text-center text-black mt-3">Tambah Data Pengeluaran</h6>
        <form action="{{ route('pengeluaran.store') }}" method="POST">
            @csrf



            <!-- Input deskripsi -->
            <label for="deskripsi" class="mt-2">Deskripsi:</label>
            <input type="text" name="deskripsi" required class="form-control">

            <!-- Input harga satuan -->
            <label for="harga_satuan" class="mt-2">Harga Satuan:</label>
            <input type="number" name="harga_satuan" id="harga_satuan" required class="form-control" oninput="hitungTotal()">

            <!-- Input volume -->
            <label for="volume" class="mt-2">Jumlah Barang:</label>
            <input type="number" name="volume" id="volume" required class="form-control" oninput="hitungTotal()">

            <!-- Input harga total (otomatis dihitung) -->
            <label for="harga_total" class="mt-2">Harga Total:</label>
            <input type="number" name="harga_total" id="harga_total" required class="form-control" readonly>

            <!-- Input keterangan -->
            <label for="keterangan" class="mt-2">Keterangan:</label>
            <input type="text" name="keterangan"  class="form-control">

            <!-- Pilihan kategori -->
            <label for="kategori" class="mt-2">Kategori:</label>
            <select name="kategori" class="form-control" required>
                <option value="">Kategori</option>
                <option value="Internet">Internet</option>
                <option value="Peralatan">Peralatan</option>
                <option value="Perlengkapan">Perlengkapan</option>
                <option value="Transport">Transport</option>
                <option value="Makan">Makan</option>
                <option value="Jasa Perbaikan">Jasa Perbaikan</option>
                <option value="Pemasangan">Pemasangan</option>
                <option value="lain-lain">Lain-lain</option>
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
