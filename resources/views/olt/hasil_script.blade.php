@extends($layout)

@section('konten')
    <div class="container my-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Hasil Script Konfigurasi</h4>
            </div>
            <div class="card-body">
                <pre class="bg-dark text-light p-3 rounded">{{ $script }}</pre>
                <a href="{{ route('olt.form.detail') }}" class="btn btn-secondary mt-3">← Kembali ke Form</a>
            </div>
        </div>
    </div>
@endsection
