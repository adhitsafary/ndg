@extends('layout_magang')

@section('konten')
    <div class="container ">
        <h2 class="mt-3">Data Magang</h2>
        <a href="{{ route('magang.create') }}" class="btn btn-primary mb-3">Tambah Data</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($data->isEmpty())
            <div class="alert alert-warning text-center">Tidak ada data magang.</div>
        @else
            <div class="row">
                @foreach ($data as $index => $d)
                    <div class="col-12 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">No.{{ $index + 1 }} - {{ $d->nama }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $d->judul }}</h6>
                                <p class="card-text"><strong>Tanggal:</strong>
                                    {{ \Carbon\Carbon::parse($d->tanggal)->format('d F Y') }}</p>
                                <p class="card-text">{{ $d->deskripsi }}</p>
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $d->foto) }}" class="img-fluid rounded"
                                        alt="foto magang"
                                        onerror="this.onerror=null; this.src='https://via.placeholder.com/150'">
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
