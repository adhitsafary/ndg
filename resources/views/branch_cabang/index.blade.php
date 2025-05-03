@extends($layout)

@section('konten')
    <div class="m-3 py-4">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-semibold m-0 text-primary">Branch / Cabang</h3>
                <a href="{{ route('cabang.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Buat Cabang Baru
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Cabang</th>
                            <th>Nama Pemilik</th>
                            <th>Alamat</th>
                            <th>Tanggal Bergabung</th>
                            <th>Kepemilikan</th>
                            <th>Persentase</th>
                            <th colspan="2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($branch_cabang as $no => $item)
                            <tr>
                                <td>{{ $no + 1 }}</td>
                                <td>{{ $item->kode_cabang }}</td>
                                <td>{{ $item->nama_cabang }}</td>
                                <td>{{ $item->nama_pemilik }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_bergabung)->format('d M Y') }}</td>
                                <td>{{ $item->Kepemilikan }}</td>
                                <td>{{ $item->persentase }}%</td>
                                <td style="padding: 2px;">
                                    <a href="{{ route('cabang.pelanggan', $item->kode_cabang) }}"
                                        class="btn btn-info btn-sm">
                                        <i class="bi bi-search"></i> Detail
                                    </a>
                                </td>


                                <td style="padding: 2px;">
                                    <a href="{{ route('cabang.edit', $item->id) }}" class="btn btn-primary btn-sm">
                                        <img src="{{ asset('asset/img/icon/edit.png') }}"
                                            style="height : 30px; width : 30px" alt="">
                                    </a>
                                </td>
                                <td style="padding: 2px;">
                                    <form action="{{ route('cabang.destroy', $item->id) }}" method="POST"
                                        class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin ingin menghapus data ini?')"
                                            class="btn btn-danger btn-sm">
                                            <img src="{{ asset('asset/img/icon/delete.png') }}"
                                                style="height: 30px; width: 30px;" alt="Hapus"></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada data cabang.</td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection
