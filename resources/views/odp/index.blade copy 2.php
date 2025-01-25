//tolong buat jadi card saja 
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

    <table class="table table-striped table-bordered">
        <thead class="table-danger">
            <tr>
                <th>No</th>
                <th>Desa</th>
                <th>Jumlah ODP</th>
                <th>Jumlah Pelanggan</th>

            </tr>
        </thead>
        <tbody>
            @forelse ($odps as $no => $odp)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>
                        <a href="{{ route('odp.showByDesa', ['desa' => $odp->desa]) }}" class="text-decoration-none">
                            {{ $odp->desa }}
                        </a>
                    </td>
                    <td>{{ $odp->jumlah_odp }}</td>
                    <td>{{ $odp->jumlah_pelanggan }}</td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
