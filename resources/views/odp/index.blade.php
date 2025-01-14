@extends($layout)

@section('konten')
    <div class="m-5">

        <h2 style="font: 600" class="text-center">Daftar ODP</h1>

            <a href="{{ route('odp.create') }}" class="btn btn-danger mb-3">Tambah ODP</a>

            <table class="table table-striped table-bordered">
                <thead class="table-danger">
                    <tr>
                        <th>Kode ODP</th>

                        <th>No Urut Odp</th>
                        <th>Jumlah Port</th>
                        <th>Jumlah Pelanggan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($odps as $odp)
                        <tr>
                            <td>{{ $odp->kode_odp }}</td>

                            <td>{{ $odp->no_urut_odp }}</td>
                            <td>{{ $odp->jml_port }}</td>
                            <td>{{ $odp->jumlah_pelanggan }}</td>
                            <td>
                                <a href="{{ route('odp.show', $odp->kode_odp) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('odp.edit', $odp->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                <!-- Form untuk menghapus ODP -->
                                <form action="{{ route('odp.destroy', $odp->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus ODP ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

    </div>
@endsection
