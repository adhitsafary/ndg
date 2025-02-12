@extends($layout)

@section('konten')
    <div class="card m-5">
        <div class=" mb-4" style="color: black;">
            <!-- Form Filter dan Pencarian -->
            <form action="{{ route('modem.index') }}" method="GET" class="form-inline mb-4 ">
                <div class="input-group">
                    <input style="color: black;" type="text" name="search" id="search" class="form-control"
                        value="{{ request('search') }}" placeholder="Pencarian">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </div>
            </form>

            <a href="/modem/create" class="btn btn-danger">Tambah Modem</a>

            <div style="display: flex; justify-content: center;" class="mb-3">
                <h5 style="color: black;" class="font font-weight-bold">Data Modem</h5>
            </div>

            <table class="table table-bordered" style="color: black;">
                <thead class="table table-danger" style="color: black;">
                    <tr>
                        <th>No</th>
                        <th>SN Modem</th>
                        <th>Model</th>
                        <th>Tanggal Keluar</th>
                        <th>User</th>
                        <th>ID MikroTik</th>
                        <th>Keterangan</th>
                        <td>Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($modem as $no => $modem)
                        <tr class="font font-weight-bold" style="color: black">
                            <td>{{ $no + 1 }}</td>
                            <td>
                                {{ preg_match('/SN:([A-Za-z0-9]+)/', $modem->sn_modem, $matches) ? $matches[1] :
                                   (preg_match('/&sn=([A-Za-z0-9]+)/', $modem->sn_modem, $matches) ? $matches[1] :
                                   (preg_match('/(ZTE[A-Za-z0-9]+)/', $modem->sn_modem, $matches) ? $matches[1] : 'Tidak ditemukan')) }}
                            </td>

                            <td>{{ $modem->model }}</td>
                            <td>{{ $modem->tgl_keluar }}</td>
                            <td>{{ $modem->user }}</td>
                            <td>{{ $modem->id_mikrotik }}</td>
                            <td>{{ $modem->keterangan }}</td>
                            <td> <a href="{{ route('modem.edit', $modem->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('modem.destroy', $modem->id) }}" method="POST"
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
@endsection
