@extends($layout)

@section('konten')
<div class="m-5">
    <h2 style="font: 600" class="text-center">DATA ODP</h2>

    <a href="{{ route('odp.create') }}" class="btn btn-danger mb-3">Tambah ODP</a>

    <!-- Form Pencarian -->
    <form method="GET" action="{{ route('odp.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Kecamatan, Desa, Dusun, Kode ODP dll" value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-primary">Cari</button>
        </div>
    </form>

    <table class="table table-striped table-bordered">
        <thead class="table-danger">
            <tr>
                <th>No</th>
                <th>Kecamatan</th>
                <th>Desa</th>
                <th>Dusun</th>
            <!--    <th>Jumlah ODP</th> -->
                <th>Kode ODP</th>
                <th>Jumlah Port</th>
                <th>No Urut Odp</th>
                <th>Jumlah Pelanggan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($odps as $no => $odp)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $odp->kecamatan }}</td>
                    <td>{{ $odp->desa }}</td>
                    <td>{{ $odp->dusun }}</td>
                  <!--  <td>{{ $odp->jml_odp }}</td> -->
                    <td>{{ $odp->kode_odp }}</td>
                    <td>{{ $odp->jml_port }}</td>
                    <td>{{ $odp->no_urut_odp }}</td>
                    <td>{{ $odp->jumlah_pelanggan }}</td>
                    <td>
                        <a href="{{ route('odp.show', $odp->kode_odp) }}" class="btn btn-info btn-sm">Detail</a>
                        <a href="{{ route('odp.edit', $odp->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('odp.destroy', $odp->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus ODP ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
