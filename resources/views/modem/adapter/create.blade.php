@extends($layout)

@section('konten')
    <div class="container mt-4">
        <h6 class="text text-center text-black mt-3"> Tambah Data Pemasukan</h6>
        <form action="{{ route('adapter.store') }}" method="POST">
            @csrf
            <!-- Input jumlah -->
            <label for="kode_barang" class=" mt-2">Kode barang:</label>
            <input type="text" name="kode_barang" required class="form-control">

            <!-- Input keterangan -->
            <label for="pic" class=" mt-2">Pic:</label>
            <input type="text" name="pic" required class="form-control"> <br>

            <label for="petugas" class=" mt-2">Petugas:</label>
            <input type="text" name="petugas" required class="form-control"> <br>

            <label for="keterangan" class=" mt-2">Keterangan:</label>
            <input type="text" name="keterangan" required class="form-control"> <br>

            <label for="tanggal" class=" mt-2">Tanggal</label>
            <input type="date" name="tanggal" required class="form-control"> <br>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        </form>


    </div>
@endsection
