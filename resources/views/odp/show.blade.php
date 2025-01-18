@extends('layout')

@section('konten')
    <div class="m-5">

        <h2 style="font: 600">Detail : ODP {{ $kode_odp }}</h2>



        <div class="row ml-0 mb-1 mt-3">

            <div class="mr-4">
                <h4 style="font-weight: 600" style="m-5">Kecamatan : {{ $kecamatan }} </h4>
            </div>
            <div class="mr-4">
                <h4 style="font-weight: 600">Desa : {{ $desa }}</h4>
            </div>
            <div class="mr-4">
                <h4 style="font-weight: 600">Dusun : {{ $dusun }}</h4>
            </div>
            <div class="mr-4">
                <h4 style="font-weight: 600">Jumlah ODP : {{ $jml_odp }}</h4>
            </div>

        </div>
        <table class="table table-striped table-bordered">
            <thead class="table-danger">
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>No Telepon</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pelanggans as $no => $pelanggan)
                    <tr>
                        <td>{{$no + 1}}</td>
                        <td>{{ $pelanggan->nama_plg }}</td>
                        <td>{{ $pelanggan->alamat_plg }}</td>
                        <td>{{ $pelanggan->no_telepon_plg }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
