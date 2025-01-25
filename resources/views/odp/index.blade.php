@extends($layout)

@section('konten')
<div class="m-5">
    <h2 style="font: 600" class="text-center">DATA ODP BERDASARKAN DESA</h2>

    <a href="{{ route('odp.create') }}" class="btn btn-danger mb-3">Tambah ODP</a>

    <!-- Form Pencarian -->
    <form method="GET" action="{{ route('odp.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control"
                placeholder="Cari berdasarkan Desa" value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-primary">Cari</button>
        </div>
    </form>

    @if ($odps->isEmpty())
        <div class="alert alert-warning text-center">
            Data tidak ditemukan.
        </div>
    @else
        <div class="row">
            @foreach ($odps as $odp)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <a href="{{ route('odp.showByDesa', ['desa' => $odp->desa]) }}" class="text-decoration-none">
                                    {{ $odp->desa }}
                                </a>
                            </h5>
                            <p class="card-text">
                                <strong>Jumlah ODP:</strong> {{ $odp->jumlah_odp }}<br>
                                <strong>Jumlah Pelanggan:</strong> {{ $odp->jumlah_pelanggan }}
                            </p>
                            <a href="{{ route('odp.showByDesa', ['desa' => $odp->desa]) }}" class="btn btn-info btn-sm">
                                Detail Desa
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
