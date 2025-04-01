@extends($layout)

@section('konten')
    <div class="container">
        <h3>Buat Pesan Baru</h3>
        <form action="{{ route('pesan.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Admin</label>
                <input type="text" name="admin" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Penerima</label>
                <input type="text" name="penerima" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Pesan</label>
                <textarea name="pesan" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>

        </form>
    </div>
@endsection
