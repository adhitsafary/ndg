@extends($layout)

@section('konten')
<div class="container">
    <h1>Daftar Token WhatsApp Bot</h1>

    <a href="{{ route('bot_tokens.create') }}" class="btn btn-primary mb-3">Tambah Token</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Token</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tokens as $token)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $token->name }}</td>
                    <td>{{ $token->token }}</td>
                    <td>
                        <a href="{{ route('bot_tokens.edit', $token) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('bot_tokens.destroy', $token) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
