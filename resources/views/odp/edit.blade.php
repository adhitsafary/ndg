@extends($layout)

@section('konten')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 style="font: 600" class="text-center">Edit ODP</h1>

                    <form action="{{ route('odp.update', $odp->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="kecamatan" class="form-label">Kecamatan</label>
                            <input type="text" class="form-control" id="kecamatan" name="kecamatan"
                                value="{{ $odp->kecamatan }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="desa" class="form-label">Desa</label>
                            <input type="text" class="form-control" id="desa" name="desa"
                                value="{{ $odp->desa }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="dusun" class="form-label">Dusun</label>
                            <input type="text" class="form-control" id="dusun" name="dusun"
                                value="{{ $odp->dusun }}" required>
                        </div>


                        <div class="mb-3">
                            <label for="kode_odp" class="form-label">Kode ODP</label>
                            <input type="text" class="form-control" id="kode_odp" name="kode_odp"
                                value="{{ $odp->kode_odp }}" required>
                        </div>


                        <div class="mb-3">
                            <label for="no_urut_odp" class="form-label">No Urut ODP</label>
                            <input type="number" class="form-control" id="no_urut_odp" name="no_urut_odp"
                                value="{{ $odp->no_urut_odp }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="jml_odp" class="form-label">Jumlah ODP</label>
                            <input type="text" class="form-control" id="jml_odp" name="jml_odp"
                                value="{{ $odp->jml_odp }}" required>
                        </div>


                        <div class="mb-3">
                            <label for="jml_port" class="form-label">Jumlah Port</label>
                            <input type="number" class="form-control" id="jml_port" name="jml_port"
                                value="{{ $odp->jml_port }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="longitude" name="longitude"
                                value="{{ $odp->longitude }}">
                        </div>

                        <div class="mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="latitude" name="latitude"
                                value="{{ $odp->latitude }}">
                        </div>

                        <button type="submit" class="btn btn-success">Perbarui ODP</button>
                        <a href="{{ route('odp.index') }}" class="btn btn-secondary">Batal</a>
                    </form>

                    <form action="{{ route('odp.destroy', $odp->id) }}" method="POST" style="margin-top: 20px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus ODP ini?')">Hapus ODP</button>
                    </form>
            </div>
        @endsection
