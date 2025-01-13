



@extends($layout)

@section('konten')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Buat Generator ID Baru</h1>
            <form action="{{ route('generator_id.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="kode_perusahaan">Kode Perusahaan</label>
                    <input type="text" name="kode_perusahaan" class="form-control" required maxlength="100">
                </div>
                <div class="form-group">
                    <label for="kode_nik">Kode NIK</label>
                    <input type="text" name="kode_nik" class="form-control" required maxlength="100">
                </div>
                <div class="form-group">
                    <label for="kode_odp">Kode ODP</label>
                    <input type="text" name="kode_odp" class="form-control" required maxlength="100">
                </div>
                <div class="form-group">
                    <label for="kode_paket_plg">Kode Paket</label>
                    <input type="text" name="kode_paket_plg" class="form-control" required maxlength="100">
                </div>


                <button type="submit" class="btn btn-success">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection



