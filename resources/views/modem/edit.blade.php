@extends($layout)

@section('konten')
<div class="card m-5">
    <h2>Edit Modem</h2>

    <form action="{{ route('modem.update', $modem->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="sn_modem" class="form-label">SN Modem</label>
            <input type="text" class="form-control" id="sn_modem" name="sn_modem" value="{{ $modem->sn_modem }}" required>
        </div>
        <div class="mb-3">
            <label for="model" class="form-label">Model</label>
            <input type="text" class="form-control" id="model" name="model" value="{{ $modem->model }}" required>
        </div>
        <div class="mb-3">
            <label for="tgl_keluar" class="form-label">Tanggal Keluar</label>
            <input type="text" class="form-control" id="tgl_keluar" name="tgl_keluar" value="{{ $modem->tgl_keluar }}" required>
        </div>
        <div class="mb-3">
            <label for="user" class="form-label">User</label>
            <input type="text" class="form-control" id="user" name="user" value="{{ $modem->user }}" required>
        </div>
        <div class="mb-3">
            <label for="id_mikrotik" class="form-label">ID MikroTik</label>
            <input type="text" class="form-control" id="id_mikrotik" name="id_mikrotik" value="{{ $modem->id_mikrotik }}" required>
        </div>
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan">{{ $modem->keterangan }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
