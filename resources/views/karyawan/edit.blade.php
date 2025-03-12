@extends('superadmin.layout_superadmin')

@section('konten')
    <div class="card m-5">
        <h6 class="text text-center text-black mt-3">Edit Data Karyawan: {{ $karyawan->nama }}</h6>

        <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="POST"> <!-- Jika route pakai POST -->


            <label>Nama Karyawan</label>
            <input type="text" name="nama" value="{{ $karyawan->nama }}" class="form-control mt-2">
            <label>KTP</label>
            <input type="text" name="ktp" value="{{ $karyawan->ktp }}" class="form-control mt-2">
            <label>Alamat</label>
            <input type="text" name="alamat" value="{{ $karyawan->alamat }}" class="form-control mt-2">
            <label>No Telepon</label>
            <input type="text" name="no_telepon" value="{{ $karyawan->no_telepon }}" class="form-control mt-2">
            <label>Posisi</label>
            <input type="text" name="posisi" value="{{ $karyawan->posisi }}" class="form-control mt-2">
            <label>Gaji</label>
            <input type="text" name="gaji" value="{{ $karyawan->gaji }}" class="form-control mt-2">
            <label>Tanggal Gajihan</label>
            <input type="date" name="tgl_gajihan" value="{{ $karyawan->tgl_gajihan }}" class="form-control mt-2">
            <label>Mulai kerja</label>
            <input type="date" name="mulai_kerja" value="{{ $karyawan->mulai_kerja }}" class="form-control mt-2">
            <label>Keterangan</label>
            <input type="text" name="keterangan" value="{{ $karyawan->keterangan }}" class="form-control mt-2">

            <div class="form-group mt-3">
                <label for="foto">Foto (Kosongkan jika tidak ingin mengganti):</label>
                <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*">
                @if ($karyawan->foto)
                    <div class="mt-2">
                        <img src="{{ asset($karyawan->foto) }}" alt="Foto Karyawan"
                            style="max-width: 100px; border-radius: 5px;">
                    </div>
                @endif
            </div>

            <button class="btn btn-primary btn-sm mt-3">Simpan</button>
        </form>
    </div>
@endsection
