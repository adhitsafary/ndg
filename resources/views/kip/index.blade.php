@extends($layout)

@section('konten')
    <div class="container card">
        <h2>Index Pekerja</h2>

        <table class="table table-bordered">
            <thead class="table-dark text-white">
                <tr>
                    <th>Nama Pekerja</th>
                    <th>Jumlah Kehadiran</th>
                    <th>Jumlah Pekerjaan</th>
                    <th>Pembayaran</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($indexPekerja as $pekerja)
                    <tr style="color: black;">
                        <td>{{ $pekerja['nama'] }}</td>
                        <td>{{ $pekerja['hadir'] }}</td>
                        <td>{{ $pekerja['pekerjaan'] }}</td>
                        <td>{{ number_format($pekerja['pembayaran'], 0, ',', '.') }}</td>
                        <td>
                            @if (!empty($pekerja['id']))
                                <a href="{{ route('kip.show', $pekerja['id']) }}" class="btn btn-info">Detail</a>
                                
                            @else
                                <span class="text-muted">Tidak Ada Detail</span>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div> <br><br>
@endsection
