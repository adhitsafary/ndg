@extends($layout)

@section('konten')
    <div class="card m-5">
        <h4 class="my-4">Tambah Pemberitahuan</h4>

            <form action="{{ route('pemberitahuan.store') }}" method="POST">
                @csrf


                <div class="mb-3">
                    <label for="pesan" class="form-label">Pesan</label>
                    <textarea name="pesan" class="form-control" id="pesan" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('pemberitahuan.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
    </div>
@endsection
