@extends($layout)

@section('konten')
    <div class="container card">
        <h3 class="text-center">Edit Data Perbaikan</h3>
        <form action="{{ route('perbaikan.update', $perbaikan->id) }}" method="POST">
            @csrf


            <label for="nama_plg">Cari Nama Pelanggan</label>
            <select id="nama_plg_select" name="nama_plg_select" class="form-control mt-2"></select> <br>

            <input type="hidden" id="nama_plg" name="nama_plg" value="{{ $perbaikan->nama_plg }}">

            <label for="id_plg">ID Pelanggan</label>
            <input type="text" id="id_plg" name="id_plg" class="form-control" value="{{ $perbaikan->id_plg }}"
                readonly>

            <label for="alamat_plg">Alamat</label>
            <input type="text" id="alamat_plg" name="alamat_plg" class="form-control"
                value="{{ $perbaikan->alamat_plg }}" readonly>

            <label for="no_telepon_plg">No Telpon</label>
            <input type="text" id="no_telepon_plg" name="no_telepon_plg" class="form-control"
                value="{{ $perbaikan->no_telepon_plg }}" readonly>

            <label for="paket_plg">Paket</label>
            <input type="text" id="paket_plg" name="paket_plg" class="form-control" value="{{ $perbaikan->paket_plg }}"
                readonly>

            <label for="odp">ODP</label>
            <input type="text" id="odp" name="odp" class="form-control" value="{{ $perbaikan->odp }}">

            <label for="maps">Maps</label>
            <input type="text" id="maps" name="maps" class="form-control" value="{{ $perbaikan->maps }}">

            <label for="teknisi">Pilih Teknisi</label>
            <div>
                @foreach ($teknisi as $tech)
                    <input type="checkbox" name="teknisi[]" value="{{ is_object($tech) ? $tech->nama : $tech }}">
                    {{ is_object($tech) ? $tech->nama : $tech }}<br>
                @endforeach

            </div>

            <label for="keterangan">Gangguan</label>
            <select id="keterangan" name="keterangan" class="form-control">
                <option value="">Pilih Gangguan</option>
                <option value="Modem error" {{ $perbaikan->keterangan == 'Modem error' ? 'selected' : '' }}>Modem error
                </option>
                <option value="Modem matot" {{ $perbaikan->keterangan == 'Modem matot' ? 'selected' : '' }}>Modem matot
                </option>
                <option value="Ganti Adaptor" {{ $perbaikan->keterangan == 'Ganti Adaptor' ? 'selected' : '' }}>Ganti
                    Adaptor</option>
                <option value="Los / modem merah" {{ $perbaikan->keterangan == 'Los / modem merah' ? 'selected' : '' }}>Los
                    / modem merah</option>
                <option value="Tidak Ada Jaringan" {{ $perbaikan->keterangan == 'Tidak Ada Jaringan' ? 'selected' : '' }}>
                    Tidak Ada Jaringan</option>
                <option value="Ganti Nama Wifi / Password"
                    {{ $perbaikan->keterangan == 'Ganti Nama Wifi / Password' ? 'selected' : '' }}>Ganti Nama / Password
                </option>
                <option value="Lain-Lain" {{ $perbaikan->keterangan == 'Lain-Lain' ? 'selected' : '' }}>Lain-Lain</option>
            </select>

            <button type="submit" class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
@endsection
