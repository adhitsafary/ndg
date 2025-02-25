@extends($layout)

@section('konten')
    <div class="card m-5">
        <div class="mb-4" style="color: black;">
            <!-- Form Filter dan Pencarian -->
            <form action="{{ route('pengeluaran.index_jml') }}" method="GET" class="form-inline mb-4">
                <div class="input-group">
                    <input style="color: black;" type="text" name="search" id="search" class="form-control"
                        value="{{ request('search') }}" placeholder="Pencarian">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-danger">Cari</button>
                    </div>
                </div>
            </form>

            <!-- Tombol Buat Pengeluaran Baru -->
            <div class="mb-3">
                <a href="/pengeluaran/create" class="btn btn-danger btn-sm">+ Pengeluaran</a>
                <!-- Tombol Export dengan Dropdown -->
                <div class="mt-3">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="exportDropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Export Data
                        </button>
                        <div class="dropdown-menu" aria-labelledby="exportDropdown">
                            <a class="dropdown-item" href="{{ route('pengeluaran.exportExcel') }}">Export Excel</a>
                            <a class="dropdown-item" href="{{ route('pengeluaran.exportPdf') }}">Export PDF</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Judul -->
            <div class="text-center mb-5">
                <h5 class="font font-weight-bold" style="color: black;">Data Pengeluaran Bulan Sekarang</h5>
            </div>

            <!-- Tabel Total Pengeluaran Per Kategori -->
            <h5 class="font-weight-bold">Total Pengeluaran Per Kategori:</h5>
            <table class="table table-bordered mb-4 text-center">
                <thead class="table-dark text-white">
                    <tr>
                        @php
                            $totalPerKategori = $totalBulanan->groupBy('kategori')->map(function ($items) {
                                return $items->sum('harga_total');
                            });
                        @endphp
                        @foreach ($totalPerKategori as $kategori => $total)
                            <th>{{ $kategori }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($totalPerKategori as $total)
                            <td>{{ number_format($total) }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>

            <!-- Tabel Pengeluaran -->
            <table class="table table-bordered" style="color: black;">
                <thead class="table" style="color: black;">
                    <tr>
                        <th>No</th>
                        <th>Deskripsi</th>
                        <th>Harga Satuan</th>
                        <th>Volume</th>
                        <th>Harga Total</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $kategoriSebelumnya = null;
                        $warnaKategori = [
                            'table-danger',
                            'table-success',
                            'table-warning',
                            'table-primary',
                            'table-info',
                        ];
                        $indexWarna = 0;
                    @endphp

                    @forelse ($totalBulanan->sortBy('kategori') as $no => $item)
                        @if ($kategoriSebelumnya !== $item->kategori)
                            @php
                                $kategoriSebelumnya = $item->kategori;
                                $warna = $warnaKategori[$indexWarna % count($warnaKategori)];
                                $indexWarna++;
                            @endphp
                            <!-- Baris Header Kategori -->
                            <tr class="{{ $warna }} font-weight-bold">
                                <td colspan="8" class="text-center">{{ $item->kategori }}</td>
                            </tr>
                        @endif

                        <tr style="color: black">
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $item->deskripsi }}</td>
                            <td>{{ number_format($item->harga_satuan) }}</td>
                            <td>{{ $item->volume }}</td>
                            <td>{{ number_format($item->harga_total) }}</td>
                            <td>{{ $item->kategori }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>
                                <a href="{{ route('pengeluaran.edit', $item->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data ditemukan</td>
                        </tr>
                    @endforelse

                    <!-- Baris total di bawah tabel -->
                    <tr class="table-danger font-weight-bold">
                        <td colspan="4" class="text-center">TOTAL</td>
                        <td>{{ number_format($totalJumlah) }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
