@extends($layout)

@section('konten')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white" style="font-weight: 700">
                Pengembalian Inventory - Perbaikan {{ $perbaikan->nama_plg }} - Pada Tanggal {{ $perbaikan->created_at }}
            </div>
            <div class="card-body">
                <form action="{{ route('inventory.processReturn', $perbaikan->id) }}" method="POST">
                    @csrf
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah Digunakan</th>
                                <th>Jumlah Dikembalikan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventoryKeluar as $nama_barang => $barang)
                                <tr>
                                    <td>{{ $nama_barang }}</td>
                                    <td>{{ $barang['jml_brg'] }}</td>
                                    <td>
                                        <input type="number" name="barang[{{ $nama_barang }}]" class="form-control" min="0" max="{{ $barang['jml_brg'] }}" value="0">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-success mt-3">Kembalikan Inventory</button>
                    <a href="{{ route('perbaikan.index') }}" class="btn btn-secondary mt-3">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
