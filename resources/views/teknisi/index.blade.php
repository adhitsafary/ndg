@extends($layout)

@section('konten')
    <div class="p-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Data Perbaikan</h5>
                    </div>
                    <div class="card-body">
                        <!-- Form Filter dan Pencarian -->
                        <form action="{{ route('teknisi.index') }}" method="GET">
                            <div class="form-group d-flex">
                                <input type="text" name="q" class="form-control me-2"
                                    placeholder="Cari berdasarkan ID atau Nama" value="{{ $query ?? '' }}">
                                <button type="submit" class="btn btn-primary w-50 ml-2">Cari</button>
                            </div>
                        </form>

                        <div class="mt-4">
                            @if ($query_cari)
                                <!-- Jika ada pencarian -->
                                @if ($pelanggan->isEmpty())
                                    <p class="text-muted">Tidak ditemukan hasil untuk "{{ $query_cari }}"</p>
                                @else
                                    <div class="row">
                                        @foreach ($pelanggan as $no => $item)
                                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{ $item->nama_plg }}</h5>
                                                        <p class="card-text"><strong>Alamat:</strong>
                                                            {{ $item->alamat_plg }}</p>
                                                        <p class="card-text"><strong>Harga:</strong>
                                                            Rp{{ number_format($item->harga_paket, 0, ',', '.') }}</p>
                                                        <p class="card-text"><strong>Tanggal Tagih:</strong>
                                                            {{ $item->tgl_tagih_plg }}</p>
                                                        <p class="card-text"><strong>Status Pembayaran:</strong>
                                                            {{ optional($item->pembayaranTerakhir)->tanggal_pembayaran
                                                                ? \Carbon\Carbon::parse($item->pembayaranTerakhir->tanggal_pembayaran)->locale('id')->isoFormat('MMMM Y')
                                                                : '-' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <!-- Jika tidak ada pencarian, tampilkan data perbaikan -->
                                <div class="row">
                                    @forelse ($perbaikan as $no => $item)
                                        <div class="col-md-4 mb-3">
                                            <div class="card h-100 shadow-sm">
                                                <div class="card-body">
                                                    <h5 style="font-weight: 1000">Perbaikan</h5>
                                                    <h6 class="card-title">{{ $item->nama_plg }}</h6>
                                                    <p class="card-text"><strong>ID Pel:</strong> {{ $item->id_plg }}</p>
                                                    <p class="card-text"><strong>Alamat:</strong> {{ $item->alamat_plg }}
                                                    </p>
                                                    <p class="card-text"><strong>No Hp:</strong>
                                                        {{ $item->no_telepon_plg }}</p>
                                                    <p class="card-text"><strong>Paket:</strong> {{ $item->paket_plg }}</p>
                                                    <p class="card-text"><strong>Odp:</strong> {{ $item->odp }}</p>
                                                    <p class="card-text"><strong>Teknisi:</strong> {{ $item->teknisi }}</p>
                                                    <p class="card-text"><strong>Keterangan:</strong>
                                                        {{ $item->keterangan }}</p>
                                                    <p class="card-text"><strong>Tanggal:</strong> {{ $item->created_at }}
                                                    </p>
                                                    <!-- <p class="card-text"><strong>Status:</strong>
                                                            {{ ucfirst($item->status) }}</p>
                                                        @if ($item->status == 'Proses')
    <form action="{{ route('perbaikan.selesai', $item->id) }}"

                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-success btn-sm">Selesai</button>
                                                            </form>
    @endif -->
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center">
                                            <p class="text-muted">Tidak ada data ditemukan</p>
                                        </div>
                                    @endforelse
                                </div>

                                <div class="row">
                                    @forelse ($rekap_pemasangan_limited as $no => $item)
                                        <div class="col-md-4 mb-3">
                                            <div class="card h-100 shadow-sm">
                                                <div class="card-body">
                                                    <h5 style="font-weight: 1000">Pemasangan Baru</h5>
                                                    <h6 class="card-title">{{ $item->nama }}</h6>
                                                    <p class="card-text"><strong>Alamat:</strong> {{ $item->alamat }}
                                                    </p>
                                                    <p class="card-text"><strong>No Hp:</strong>
                                                        {{ $item->no_telpon }}</p>
                                                    <p class="card-text"><strong>Paket:</strong> {{ $item->paket_plg }}</p>

                                                    <p class="card-text"><strong>Tanggal:</strong> {{ $item->created_at }}
                                                    </p>
                                                    <!-- <p class="card-text"><strong>Status:</strong>
                                                            {{ ucfirst($item->status) }}</p>
                                                        @if ($item->status == 'Proses')
    <form action="{{ route('perbaikan.selesai', $item->id) }}"

                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-success btn-sm">Selesai</button>
                                                            </form>
    @endif -->
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center">
                                            <p class="text-muted">Tidak ada data ditemukan</p>
                                        </div>
                                    @endforelse
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
