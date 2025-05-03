@extends($layout)

@section('konten')
    <div class="card m-3">
        <h6 class=" text-center text-black mt-3">Buat Cabang Baru</h6>
        <form action="{{ route('cabang.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Kode Cabang</label>
                <input type="text" name="kode_cabang" class="form-control"
                    value="{{ old('kode_cabang', $cabang->kode_cabang ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label>Nama Cabang</label>
                <input type="text" name="nama_cabang" class="form-control"
                    value="{{ old('nama_cabang', $cabang->nama_cabang ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label>Nama Pemilik</label>
                <input type="text" name="nama_pemilik" class="form-control"
                    value="{{ old('nama_pemilik', $cabang->nama_pemilik ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" required>{{ old('alamat', $cabang->alamat ?? '') }}</textarea>
            </div>
            <div class="mb-3">
                <label>Tanggal Bergabung</label>
                <input type="date" name="tanggal_bergabung" class="form-control"
                    value="{{ old('tanggal_bergabung', $cabang->tanggal_bergabung ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label>Kepemilikan</label>
                <input type="text" name="Kepemilikan" class="form-control"
                    value="{{ old('Kepemilikan', $cabang->Kepemilikan ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label>Persentase</label>
                <input type="number" name="persentase" class="form-control"
                    value="{{ old('persentase', $cabang->persentase ?? '') }}" required>
            </div>

            <button class="btn btn-primary" type="submit">Simpan</button>
        </form>
    </div>
@endsection
