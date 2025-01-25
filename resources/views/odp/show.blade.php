@extends('layout')

@section('konten')
    <diV class="card m-5">
        <div class="">
            <div class="row ml-3 mb-3 mt-1 ">

                <div class="d-flex justify-content-center">
                    <img src="{{ asset('asset/img/icon/odp2.png') }}" height="100px" alt="">
                </div>
                <div class="">
                    <h2 style="font: 600" class="ml-4">Detail : ODP {{ $kode_odp }}</h2>
                    <div class="row ml-4">
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
                        <div class="mr-4">
                            <h4 style="font-weight: 600">Keterangan : {{ $keterangan }}</h4>
                        </div>
                    </div>
                </div>

            </div>
            <table class="table table-striped table-bordered">
                <thead class="table-danger">
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Alamat</th>
                        <th>No Telepon</th>
                        <th>Paket</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($pelanggans as $no => $pelanggan)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $pelanggan->nama_plg }}</td>
                            <td>{{ $pelanggan->alamat_plg }}</td>
                            <td>{{ $pelanggan->no_telepon_plg }}</td>
                            <td>{{ $pelanggan->paket_plg }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </diV>
@endsection
