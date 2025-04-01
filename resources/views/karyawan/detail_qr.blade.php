@extends('layout_qr')

@section('konten') <br>
    <div class=" m-2">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Karyawan Net Digital Group</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="card col-md-2 d-flex flex-column align-items-center justify-content-center mb-3">

                        <div class="card p-2 d-flex justify-content-center align-items-center">
                            <img src="{{ asset($karyawan->foto) }}" alt="Foto Karyawan"
                                style="max-width: 250px; max-height: 250px; border-radius: 5%;">
                        </div>
                    </div>


                    <div class="col-md-5 card mb-4">
                        <h5 style="color: black" class="fotn font-weight-bold ">Informasi Karyawan</h5>
                        <ul class="list-group  font-weight-bold" style="color: black">
                            <li class="list-group-item">
                                <strong>Nama :</strong> {{ $karyawan->nama }}
                            </li>

                            <li class="list-group-item">
                                <strong>Alamat :</strong> {{ $karyawan->alamat }}
                            </li>



                        </ul>
                    </div>

                    <div class="col-md-5 card">
                        <h5 style="color: black" class=" font-weight-bold mt-3">Detail Karyawan</h5>
                        <ul class="list-group  font-weight-bold" style="color: black">
                            <li class="list-group-item">
                                <strong>Jabatan:</strong> {{ $karyawan->posisi }}
                            </li>
                            <li class="list-group-item">
                                <strong>Mulai Kerja :</strong> {{ $karyawan->mulai_kerja }}
                            </li>
                            <li class="list-group-item">
                                <strong>Keterangan :</strong> {{ $karyawan->keterangan }}
                            </li>


                        </ul>
                    </div>
                </div>





            </div>

        </div>
    </div>
    </div>
@endsection
