@extends('layout')

@section('konten')
    <div class="m-5">
        <h2 style="font: 600">Detail: {{ $kode_odp }}</h1> <br>

            <table class="table table-striped">
                <thead class="table-danger">
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Alamat</th>
                        <th>No Telepon</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pelanggans as $pelanggan)
                        <tr>
                            <td>{{ $pelanggan->nama_plg }}</td>
                            <td>{{ $pelanggan->alamat_plg }}</td>
                            <td>{{ $pelanggan->no_telepon_plg }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
@endsection
