@extends($layout)

@section('konten')
    <div class=" ml-5 mr-5 mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h2 class="mb-0">Edit Pelanggan</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('registerpelangganbaru.update', $pelanggan_baru->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama_plg" value="{{ $pelanggan_baru->nama_plg }}"
                            required>
                    </div>
                    <div class="form-group mb-3">
                        <label>NIK</label>
                        <input type="text" class="form-control" name="nik_plg" value="{{ $pelanggan_baru->nik_plg }}"
                            required>
                    </div>
                    <div class="form-group mb-3">
                        <label>No. Telepon</label>
                        <input type="text" class="form-control" name="no_tlp_plg"
                            value="{{ $pelanggan_baru->no_tlp_plg }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email_plg" value="{{ $pelanggan_baru->email_plg }}"
                            required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Alamat</label>
                        <input type="text" class="form-control" name="alamat_plg"
                            value="{{ $pelanggan_baru->alamat_plg }}" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        <a href="{{ route('registerpelangganbaru.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
