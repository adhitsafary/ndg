@extends($layout)

@section('konten')
    <div class="container card">
        <h3 style="font-weight: 1000">Daftar Inventory</h3> <br>
        <div>
            <a href="{{ route('inventory.create') }}" class="btn btn-primary">Tambah Barang</a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger" style="background: #a72828; color: white; border: 1px solid #ff0000;">
                {{ session('error') }}
            </div>
        @endif

        @if (session('alert'))
            <div class="alert alert-dismissible fade show" role="alert"
                style="background: #a72828; color: white; border: 1px solid #ff0000;">
                {{ session('alert') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            $groupedInventories = $inventories->groupBy('kategori');
            $totalKeseluruhan = $inventories->sum('jml_brg');
        @endphp

        @foreach ($groupedInventories as $kategori => $items)
            @php
                $totalJumlahKategori = $items->sum('jml_brg');
                $persentaseKategori =
                    $totalKeseluruhan > 0 ? round(($totalJumlahKategori / $totalKeseluruhan) * 100) : 0;
            @endphp

            <h5 style="font-weight: 1000" class="mt-4">Kategori: {{ $kategori }}</h5>
            <div class="progress mb-2" style="height: 20px;">
                <div class="progress-bar" role="progressbar" style="width: {{ $persentaseKategori }}%;"
                    aria-valuenow="{{ $persentaseKategori }}" aria-valuemin="0" aria-valuemax="100">
                    {{ $persentaseKategori }}%
                </div>
            </div>

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Harga Satuan</th>
                        <th>Harga Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalJumlah = 0;
                        $totalHarga = 0;
                    @endphp
                    @foreach ($items as $inventory)
                        <tr>
                            <td>{{ $inventory->nm_brg }}</td>
                            <td>{{ $inventory->jml_brg }}</td>
                            <td>{{ $inventory->satuan }}</td>
                            <td>Rp{{ number_format($inventory->harga_satuan, 2) }}</td>
                            <td>Rp{{ number_format($inventory->harga_total, 2) }}</td>
                            <td>
                                <a href="{{ route('inventory.edit', $inventory->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @php
                            $totalJumlah += $inventory->jml_brg;
                            $totalHarga += $inventory->harga_total;
                        @endphp
                    @endforeach
                    <tr class="fw-bold bg-light font-weight: 1000">
                        <td style="font-weight: 1000">Total Stok</td>
                        <td style="font-weight: 1000">{{ $totalJumlah }} PCS/METER</td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: 1000">Rp{{ number_format($totalHarga, 2) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('success'))
                document.getElementById("successMessage").innerText = "✅ {{ session('success') }}";
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            @endif
        });
    </script>
@endsection
