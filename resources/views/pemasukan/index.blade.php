@extends($layout)

@section('konten')
    <div class="card m-5">
        <div class="mb-4" style="color: black;">
            <!-- Form Filter dan Pencarian -->
            <form action="{{ route('pemasukan.index') }}" method="GET" class="form-inline mb-4 ">
                <div class="input-group">
                    <input style="color: black;" type="text" name="search" id="search" class="form-control"
                        value="{{ request('search') }}" placeholder="Pencarian">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-danger">Cari</button>
                    </div>
                </div>
            </form>

            <a href="/pemasukan/create" class="btn btn-danger">Buat Pemasukan</a>

            <a href="/pemasukan/index_jml/" class="btn btn-danger">Data Pemasukan 1 Bulan</a>

            <div style="display: flex; justify-content: center;" class="mb-3">

                <h5 style="color: black;" class="font font-weight-bold">Data Pemasukan</h5>
            </div>

            <table class="table table-bordered" style="color: black;">
                <thead class="table table-danger" style="color: black;">
                    <tr>
                        <th>No</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pemasukan as $no => $item)
                        <tr class="font font-weight-bold" style="color: black">
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>{{ number_format($item->jumlah) }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td> <a href="{{ route('pemasukan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                <form action="{{ route('pemasukan.destroy', $item->id) }}" method="POST"
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
