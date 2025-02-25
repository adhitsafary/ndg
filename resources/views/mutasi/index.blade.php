@extends($layout)

@section('konten')
    <div class="mt-5">
        <div class="d-flex justify-content-center align-items-center ">
            <div class="container">
                <div class="card p-4">
                    <br>
                    <div class="">
                        <h4 class="text-center">Rekap Mutasi Harian</h4>
                    </div>
                    <br>

                    <div class="card">
                        <table border="1" class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th class="text-center" style="padding: 1%">Tanggal Tagihan</th>
                                    <th class="text-center" style="padding: 1%">Jumlah Pelanggan</th>
                                    <th class="text-center" style="padding: 1%">Total Pembayaran</th>
                                    <th class="text-center" style="padding: 1%">Total Pembayaran Diterima</th>
                                    <th class="text-center" style="padding: 1%">Selisih Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pemasukan as $no => $item)
                                    <tr class="font font-weight-bold" style="color: black">
                                        <td>{{ is_numeric($no) ? $no + 1 : '' }}</td>
                                        <td>{{ is_numeric($item['jumlah']) ? number_format($item['jumlah']) : $item['jumlah'] }}
                                        </td>
                                        <td>{{ $item['keterangan'] }}</td>
                                        <td>{{ $item['created_at'] }}</td>
                                        <td>
                                            @if (is_numeric($no))
                                                <a href="{{ route('pemasukan.edit', $item['id']) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('pemasukan.destroy', $item['id']) }}" method="POST"
                                                    class="d-inline-block">
                                                    @csrf
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>



                    </div>

                    <div>
                        <table class="table table-bordered" style="color: black;">
                            <thead class="table table-danger" style="color: black;">
                                <tr>
                                    <th>Total Pemasukan Bulan Ini</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ number_format($totalBulanan) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
