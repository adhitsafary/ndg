@extends($layout)

@section('konten')
    <div class=" ml-5 mr-5 mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h2 class="mb-0">Daftar Pelanggan</h2>
            </div>
            <div class="card-body">
                <a href="{{ route('registerpelangganbaru.create') }}" class="btn btn-primary mb-3">Tambah Pelanggan</a>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>No. Telepon</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pelanggan_baru as $index => $p)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $p->nama_plg }}</td>
                                <td>{{ $p->nik_plg }}</td>
                                <td>{{ $p->no_tlp_plg }}</td>
                                <td>{{ $p->email_plg }}</td>
                                <td>{{ $p->alamat_plg }}</td>
                                <td>
                                    <a href="{{ route('registerpelangganbaru.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('registerpelangganbaru.destroy', $p->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            
        </div>
    </div>
@endsection
