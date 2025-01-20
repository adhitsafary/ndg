@extends($layout)

@section('konten')
    <div class="container">
        <h1>Edit Token WhatsApp Bot</h1>

        <form action="{{ route('bot_tokens.update', $token) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama Token</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $token->name }}" required>
            </div>
            <div class="mb-3">
                <label for="token" class="form-label">Token</label>
                <input type="text" id="token" name="token" class="form-control" value="{{ $token->token }}"
                    required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
