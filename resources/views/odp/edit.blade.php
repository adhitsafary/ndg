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
                            <label for="nama_odp" class="form-label">Nama ODP</label>
                            <input type="text" class="form-control" id="nama_odp" name="nama_odp"
                                value="{{ $odp->nama_odp }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="jml_port" class="form-label">Jumlah ODP</label>
                            <input type="number" class="form-control" id="jml_port" name="jml_port"
                                value="{{ $odp->jml_port }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="longitude" name="longitude"
                                value="{{ $odp->longitude }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="latitude" name="latitude"
                                value="{{ $odp->latitude }}" required>
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
