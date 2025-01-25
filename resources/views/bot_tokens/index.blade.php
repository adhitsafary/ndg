@extends($layout)

@section('konten')
    <div class="card m-5">
        <h4>Daftar Token WhatsApp Bot</h4>

        <div class="row ml-2">
            <a href="{{ route('bot_tokens.create') }}" class="btn btn-primary mb-3 mr-2">Tambah Token</a>
            <a href="/bot_tokens/show/" class="btn btn-primary mb-3">Buat Token baru</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>NO</th>
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
    </div>
@endsection
