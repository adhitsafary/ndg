@extends($layout)

@section('konten')
    <div class="card m-5">
        <!-- Form Filter dan Pencarian -->
        <form action="{{ route('pengeluaran.index') }}" method="GET" class="form-inline mb-4">
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Pencarian">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-danger">Cari</button>
                </div>
            </div>
        </form>

        <div class="row">
            <a href="/pengeluaran/create" class="btn btn-danger mr-2 ml-3">Buat Pengeluaran</a>
            <a href="/pengeluaran/index_jml" class="btn btn-danger">Data Pengeluaran 1 Bulan</a>
        </div>

        <div style="display: flex; justify-content: center;" class="mb-3">
            <h5 style="color: black;" class="font font-weight-bold">Data Pengeluaran</h5>
        </div>

        <table class="table table-bordered" style="color: black;">
            <thead class="table " style="color: black;">
                <tr>
                    <th>No</th>
                    <th>Deskripsi</th>
                    <th>Harga Satuan</th>
                    <th>Volume</th>
                    <th>Harga Total</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $kategoriSebelumnya = null;
                    $warnaKategori = ['table-danger', 'table-success', 'table-warning', 'table-primary', 'table-info'];
                    $indexWarna = 0;
                @endphp

                @forelse ($pengeluaran->sortBy('kategori') as $no => $item)
                    @if ($kategoriSebelumnya !== $item->kategori)
                        @php
                            $kategoriSebelumnya = $item->kategori;
                            $warna = $warnaKategori[$indexWarna % count($warnaKategori)]; // Warna bergantian
                            $indexWarna++;
                        @endphp
                        <!-- Baris Header Kategori -->
                        <tr class="{{ $warna }} font-weight-bold">
                            <td colspan="9" class="text-center">{{ $item->kategori }}</td>
                        </tr>
                    @endif

                    <tr style="color: black">
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $item->deskripsi }}</td>
                        <td>{{ number_format($item->harga_satuan) }}</td>
                        <td>{{ number_format($item->volume) }}</td>
                        <td>{{ number_format($item->harga_total) }}</td>
                        <td>{{ $item->kategori }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            <a href="{{ route('pengeluaran.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
