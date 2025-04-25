@extends('layout_daptar')

@section('konten')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg rounded">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Login Pelanggan</h3>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('login.pelanggan') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="id_plg">ID Pelanggan</label>
                                <input type="text" name="id_plg" id="id_plg" class="form-control"
                                    placeholder="Masukkan ID Pelanggan" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block mt-3">Login</button>
                        </form>

                        <p class="text-center text-muted mt-4">© {{ date('Y') }} Sistem Net Digital Group</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
