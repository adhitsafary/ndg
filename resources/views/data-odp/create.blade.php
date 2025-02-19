// Blade View: data-odp/create.blade.php
@extends('layouts.app')

@section('konten')
    <div class="card m-5">
        <h4>Tambah Data ODP</h4>
        <form action="{{ route('data-odp.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="nama">Nama ODP</label>
                <input type="text" name="nama" id="nama" required>
            </div>
            <div>
                <label for="tipe">Tipe</label>
                <select name="tipe" id="tipe" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="ODP">ODP</option>
                    <option value="ODC">ODC</option>
                    <option value="Crosure">Crosure</option>
                    <option value="Tiang">Tiang</option>
                    <option value="Rumah">Rumah</option>
                </select>
            </div>
            <div>
                <label for="latitude">Latitude</label>
                <input type="text" name="latitude" id="latitude" required readonly>
            </div>
            <div>
                <label for="longitude">Longitude</label>
                <input type="text" name="longitude" id="longitude" required readonly>
            </div>
            <div>
                <label for="foto">Unggah Foto</label>
                <input type="file" name="foto" id="foto" accept="image/*" required>
            </div>
            <button type="submit">Simpan</button>
        </form>
        <script>
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                });
            } else {
                alert("Geolocation tidak didukung oleh browser Anda.");
            }
        </script>

    </div>

    <script>
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
            });
        } else {
            alert("Geolocation tidak didukung di browser ini.");
        }
    </script>
@endsection
