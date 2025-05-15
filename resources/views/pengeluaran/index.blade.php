@extends($layout)

@section('konten')
    <div class="ml-5 mr-5 mt-3">
        <form action="{{ route('pengeluaran.index') }}" method="GET" class="form-inline mb-4">
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Pencarian">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-danger">Cari</button>
                </div>
            </div>
        </form>


        <div class="card shadow-lg p-3">
            <h3 style="font-weight: 1000">Daftar Pengeluaran</h3>
            <div class="mb-3">
                <a href="{{ route('pengeluaran.create') }}" class="btn btn-primary btn-sm">Tambah Pengeluaran +</a>
                <a href="/pengeluaran/index_jml" class="btn btn-danger btn-sm">Data Pengeluaran 1 Bulan</a>
            </div>

            @if (session('error'))
                <div class="alert alert-danger" style="background: #a72828; color: white; border: 1px solid #ff0000;">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('alert'))
                <div class="alert  alert-dismissible fade show" role="alert"
                    style="background: #a72828; color: white; border: 1px solid #ff0000;">
                    {{ session('alert') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-dismissible fade show" role="alert"
                    style="background: #28a745; color: white; border: 1px solid #218838;">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @php
                $groupedPengeluaran = $pengeluaran->groupBy('kategori');
                $totalKeseluruhan = $pengeluaran->sum('harga_total');
            @endphp
            <div class="table-responsive">
                <table class="table mt-3 table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Kategori</th>
                            <th>Persentase (%)</th>
                            <th>Total Pengeluaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedPengeluaran as $kategori => $items)
                            @php
                                $totalKategori = $items->sum('harga_total');
                                $persentaseKategori =
                                    $totalKeseluruhan > 0 ? round(($totalKategori / $totalKeseluruhan) * 100) : 0;
                            @endphp
                            <tr>
                                <td>{{ $kategori }}</td>
                                <td>{{ $persentaseKategori }}%</td>
                                <td>Rp{{ number_format($totalKategori, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th>Total Keseluruhan</th>
                            <th>100%</th>
                            <th>Rp{{ number_format($totalKeseluruhan, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @foreach ($groupedPengeluaran as $kategori => $items)
                @php
                    $totalKategori = $items->sum('harga_total');
                    $persentaseKategori = $totalKeseluruhan > 0 ? round(($totalKategori / $totalKeseluruhan) * 100) : 0;
                @endphp

                <h5 class="mt-4">Kategori: {{ $kategori }} ({{ $persentaseKategori }}%)</h5>
                <div class="progress mb-2" style="height: 20px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $persentaseKategori }}%;">
                        {{ $persentaseKategori }}%
                    </div>
                </div>

                <!-- Tombol untuk menampilkan/sembunyikan tabel -->
                <button class="btn btn-secondary my-3" type="button" data-bs-toggle="collapse"
                    data-bs-target="#tabelPengeluaran" aria-expanded="false" aria-controls="tabelPengeluaran">
                    Tampilkan / Sembunyikan Detail Pengeluaran
                </button>

                <!-- Elemen collapse -->
                <div class="collapse" id="tabelPengeluaran">
                    <div class="table-responsive">
                        <table class="table mt-3 table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Deskripsi</th>
                                    <th>Harga Satuan</th>
                                    <th>Volume</th>
                                    <th>Harga Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->deskripsi }}</td>
                                        <td>Rp{{ number_format($item->harga_satuan, 2) }}</td>
                                        <td>{{ $item->volume }}</td>
                                        <td>Rp{{ number_format($item->harga_total, 2) }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap">
                                                <a href="{{ route('pengeluaran.edit', $item->id) }}"
                                                    class="btn btn-warning btn-sm me-1">Edit</a>
                                                <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div> <br><br>
@endsection
