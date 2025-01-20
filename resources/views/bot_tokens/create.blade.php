@extends($layout)

@section('konten')
<div class="container">
    <h1>Tambah Token WhatsApp Bot</h1>

    <form action="{{ route('bot_tokens.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama Token</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="token" class="form-label">Token</label>
            <input type="text" id="token" name="token" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
