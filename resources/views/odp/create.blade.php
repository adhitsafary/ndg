@extends($layout)

@section('konten')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 style="font: 600" class="text-center">Tambah ODP Baru</h2>

                <form action="{{ route('odp.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_odp" class="form-label">Nama ODP</label>
                        <input type="text" class="form-control" id="nama_odp" name="nama_odp" required>
                    </div>

                    <div class="mb-3">
                        <label for="jml_port" class="form-label">Jumlah ODP</label>
                        <input type="number" class="form-control" id="jml_port" name="jml_port" required>
                    </div>

                    <div class="mb-3">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" required>
                    </div>

                    <div class="mb-3">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" required>
                    </div>

                    <button type="submit" class="btn btn-success">Tambah ODP</button>
                </form>
            </div>
        @endsection
