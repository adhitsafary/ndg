@extends('layout_login')

@section('konten')
    <div class="container">
        <form action="{{ route('data-odp.update', $data_odp->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Deskripsi:</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $data_odp->nama) }}" required>
            </div>
            <div>
                <select name="tipe" id="tipe" required>
                    <option value="ODP" {{ $data_odp->tipe == 'ODP' ? 'selected' : '' }}>ODP</option>
                    <option value="ODC" {{ $data_odp->tipe == 'ODC' ? 'selected' : '' }}>ODC</option>
                    <option value="Crosure" {{ $data_odp->tipe == 'Crosure' ? 'selected' : '' }}>Crosure</option>
                    <option value="Tiang" {{ $data_odp->tipe == 'Tiang' ? 'selected' : '' }}>Tiang</option>
                </select>
            </div>
            <div class="form-group">
                <label>Maps:</label>
                <input type="text" name="maps" class="form-control" value="{{ old('maps', $data_odp->maps) }}" required>
            </div>
            <div class="form-group">
                <label>Foto:</label>
                <div>
                    <img src="{{ asset($data_odp->foto) }}" alt="Foto Sebelumnya" width="100" class="mb-2">
                    <input type="file" name="foto" id="foto" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>

    </div>


@endsection
