@extends($layout)

@section('konten')
    <div class="card m-5">
        <div class="mb-4" style="color: black;">
            <!-- Form Filter dan Pencarian -->
            <form action="{{ route('pemasukan.index_jml') }}" method="GET" class="form-inline mb-4 ">
                <div class="input-group">
                    <input style="color: black;" type="text" name="search" id="search" class="form-control"
                        value="{{ request('search') }}" placeholder="Pencarian">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-danger">Cari</button>
                    </div>
                </div>
            </form>

            <!-- Tombol Buat Pemasukan Baru -->
            <div class="d-flex justify-right-center mb-3">
                <a href="/pemasukan/create" class="btn btn-danger btn-lg w-100">Buat Pemasukan Baru</a>
            </div>


            <!-- Judul -->
            <div class="text-center mb-3">
                <h5 class="font font-weight-bold" style="color: black;">Data Pemasukan Bulan Sekarang</h5>
            </div>

            <!-- Tombol Export dengan Dropdown -->
            <div class="d-flex justify-right-center mb-3">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Export Data
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('pemasukan.exportExcel') }}">Export Excel</a>
                        <a class="dropdown-item" href="{{ route('pemasukan.exportPdf') }}">Export PDF</a>
                    </div>
                </div>
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
                    @forelse ($totalBulanan as $no => $item)
                        <tr class="font font-weight-bold" style="color: black">
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>{{ number_format($item->jumlah) }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>
                                <a href="{{ route('pemasukan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
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
                            <td colspan="5" class="text-center">Tidak ada data ditemukan</td>
                        </tr>
                    @endforelse

                    <!-- Baris total di bawah tabel -->
                    <tr class="table-danger font-weight-bold">
                        <td colspan="1" class="text-center">TOTAL</td>
                        <td>{{ number_format($totalJumlah) }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
@endsection
