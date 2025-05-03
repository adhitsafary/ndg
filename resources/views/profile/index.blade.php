@extends($layout) {{-- Sesuaikan layout --}}
@section('konten')
    <div class="card m-4">
        <h4>Edit Profile</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf


            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" readonly>
            </div>

            <div class="form-group">
                <label>Saldo</label>
                <input type="text" name="saldo" class="form-control"
                    value=" {{ number_format(Auth::user()->saldo, 0, ',', '.') }} " readonly>
            </div>


            <div class="form-group">
                <label>Foto Profil</label><br>
                @if ($user->foto)
                    <img src="{{ asset('foto_profile/' . $user->foto) }}" width="80" class="mb-2">
                @endif
                <input type="file" name="foto" class="form-control">
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password" class="form-control">
                <small>Kosongkan jika tidak ingin mengganti password</small>
            </div>

            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary mt-2">Simpan</button>
        </form>

        {{-- resources/views/profile/show.blade.php --}}
        <div class="mt-4">
            <p class="font-bold">QR Code Anda:</p>
            {!! QrCode::size(150)->generate(Auth::user()->id) !!}
        </div>

    </div>
@endsection
