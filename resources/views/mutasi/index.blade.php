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
                                @foreach ($mergedResults as $data)
                                    <tr>
                                        <td class="text-center" style="padding: 1%">{{ $data['tgl_tagih_plg'] }}</td>
                                        <td class="text-center" style="padding: 1%">{{ $data['jumlah_pelanggan'] }}</td>
                                        <td class="text-center" style="padding: 1%">
                                            {{ number_format($data['total_pembayaran'], 0, ',', '.') }}</td>
                                        <td class="text-center" style="padding: 1%">
                                            <a
                                                href="{{ route('pelanggan.index', ['tgl_tagih_plg' => $data['tgl_tagih_plg'], 'status_pembayaran' => 'paid']) }}">
                                                {{ number_format($data['total_pembayaran_diterima'], 0, ',', '.') }}
                                            </a>
                                        </td>
                                        <td class="text-center" style="padding: 1%">
                                            <a
                                                href="{{ route('pelanggan.isolir', ['tgl_tagih_plg' => $data['tgl_tagih_plg']]) }}">
                                                {{ number_format($data['selisih_pembayaran'], 0, ',', '.') }}
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
