@extends($layout)

@section('konten')
    <div class="card m-5">

        <div class="">
            <h2 style="font: 600" class="text-center">DETAIL ODP DI DESA {{ strtoupper($desa) }}</h2>

            <a href="{{ route('odp.index') }}" class="btn btn-primary mb-3">Kembali</a>

            <table class="table table-striped table-bordered">
                <thead class="table-danger">
                    <tr>
                        <th>No</th>
                        <th>Kecamatan</th>
                        <th>Desa</th>
                        <th>Dusun</th>
                        <th>Keterangan</th>
                        <th>Kode ODP</th>
                        <th>Jumlah Port</th>
                        <th>No Urut ODP</th>
                        <th>Jumlah Pelanggan</th>
                        <th>Keterangan</th>
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
                            <td>{{ $odp->keterangan }}</td>
                            <td>{{ $odp->kode_odp }}</td>
                            <td>{{ $odp->jml_port }}</td>
                            <td>{{ $odp->no_urut_odp }}</td>
                            <td>{{ $odp->jumlah_pelanggan }}</td>
                            <td>{{ $odp->keterangan }}</td>
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
                            <td colspan="6" class="text-center">Data tidak ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
