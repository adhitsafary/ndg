@extends($layout)

@section('konten')
    <div class="container">
        <h3>Edit Pesan</h3>
        <form action="{{ route('pesan.update', $pesan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Admin</label>
                <input type="text" name="admin" class="form-control" value="{{ $pesan->admin }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Penerima</label>
                <input type="text" name="penerima" class="form-control" value="{{ $pesan->penerima }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Pesan</label>
                <textarea name="pesan" class="form-control" required>{{ $pesan->pesan }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
@endsection
