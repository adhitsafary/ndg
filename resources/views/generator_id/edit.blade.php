
@extends($layout)

@section('konten')
<div class="container">
    <h2>Edit Id Pelanggan</h2>

    <form action="{{ route('generator_id.update', $generatorId->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="kode_perusahaan" class="form-label">Kode Perusahaan</label>
            <input type="text" class="form-control" id="kode_perusahaan" name="kode_perusahaan" value="{{ $generatorId->kode_perusahaan }}" required>
        </div>
        <div class="mb-3">
            <label for="kode_paket_plg" class="form-label">Paket Pelanggan</label>
            <input type="text" class="form-control" id="kode_paket_plg" name="kode_paket_plg" value="{{ $generatorId->kode_paket_plg }}" required>
        </div>
        <div class="mb-3">
            <label for="kode_nik" class="form-label">Nik Pelanggan</label>
            <input type="text" class="form-control" id="kode_nik" name="kode_nik" value="{{ $generatorId->kode_nik }}" required>
        </div>
        <div class="mb-3">
            <label for="kode_odp" class="form-label">Kode Odp</label>
            <input type="text" class="form-control" id="kode_odp" name="kode_odp" value="{{ $generatorId->kode_odp }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
