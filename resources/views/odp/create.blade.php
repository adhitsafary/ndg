@extends($layout)

@section('konten')
    <div class="card m-5 " style="align-content: center">
        <div class="row">
            <div class="col-12">
                <h2 style="font: 600" class="text-center">Tambah ODP Baru</h2>

                <form action="{{ route('odp.store') }}" method="POST">
                    @csrf


                    <div class="mb-3">
                        <label for="kecamatan" class="form-label">Kecamatan</label>
                        <input type="text" class="form-control" id="kecamatan" name="kecamatan" required>
                    </div>

                    <div class="mb-3">
                        <label for="desa" class="form-label">Desa</label>
                        <input type="text" class="form-control" id="desa" name="desa" required>
                    </div>
                    <div class="mb-3">
                        <label for="dusun" class="form-label">Dusun</label>
                        <input type="text" class="form-control" id="dusun" name="dusun" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" required>
                    </div>
                    <div class="mb-3">
                        <label for="jml_odp" class="form-label">Jumlah ODP</label>
                        <input type="text" class="form-control" id="jml_odp" name="jml_odp" required>
                    </div>


                    <div class="mb-3">
                        <label for="kode_odp" class="form-label">Kode ODP</label>
                        <input type="text" class="form-control" id="kode_odp" name="kode_odp" required>
                    </div>

                    <div class="mb-3">
                        <label for="no_urut_odp" class="form-label">Nomer Urut ODP</label>
                        <input type="number" class="form-control" id="no_urut_odp" name="no_urut_odp" required>
                    </div>
                    <div class="mb-3">
                        <label for="jml_port" class="form-label">Jumlah Port</label>
                        <input type="number" class="form-control" id="jml_port" name="jml_port" required>
                    </div>

                    <div class="mb-3">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude">
                    </div>

                    <div class="mb-3">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude">
                    </div>

                    <button type="submit" class="btn btn-success">Tambah ODP</button>
                </form>
            </div>

        </div>
    </div>
@endsection
