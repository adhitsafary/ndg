@extends($layout)

@section('konten')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>User ID Pelanggan</h1>
                <a href="{{ route('generator_id.create') }}" class="btn btn-danger mb-3">Buat User ID Baru</a>

                <table class="table table-bordered" style="color: black;">
                    <thead class="table table-danger" style="color: black;">
                        <tr>
                            <th>No</th>
                            <th>Id Pelanggan</th>
                            <th>Nama Pelanggan</th>
                            <th>Kode Perusahaan</th>
                            <th>Nik</th>
                            <th>ODP</th>
                            <th>Paket</th>
                            <th>Hasil</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($generatorIds as $no => $generatorId)
                            <tr class="font font-weight-bold" style="color: black">
                                <td>{{ $no + 1 }}</td>
                                <td>{{ $generatorId->id_plg }}</td>
                                <td>{{ $generatorId->nama_plg }}</td>
                                <td>{{ $generatorId->kode_perusahaan }}</td>
                                <td>{{ $generatorId->kode_nik }}</td>
                                <td>{{ $generatorId->kode_odp }}</td>
                                <td>{{ $generatorId->kode_paket_plg }}</td>
                                <td>{{ $generatorId->kode_unik }}</td>
                                <td> <a href="{{ route('generator_id.edit', $generatorId->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>

                                    <form action="{{ route('generator_id.destroy', $generatorId->id) }}" method="POST"
                                        class="d-inline-block">
                                        @csrf

                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center">Tidak ada data ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>



            </div>
        </div>
    </div>
@endsection
