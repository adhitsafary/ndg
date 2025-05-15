@extends('layout_qr')

@section('konten')
    <div class="container">
        <h2>Tambah Data Magang</h2>
        <form action="{{ route('magang.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('magang.form')
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
