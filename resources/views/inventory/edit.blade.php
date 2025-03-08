@extends($layout)

@section('konten')
    <div class="container card">
        <h1>Edit Barang</h1>
        <form action="{{ route('inventory.update', $inventory->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama Barang</label>
                <select name="nm_brg" class="form-control" required>
                    <option value="">- Pilih -</option>
                    <option value="Modem GPON" {{ $inventory->nm_brg == 'Modem GPON' ? 'selected' : '' }}>Modem GPON</option>
                    <option value="Modem EPON" {{ $inventory->nm_brg == 'Modem EPON' ? 'selected' : '' }}>Modem EPON</option>
                    <option value="Modem XPON" {{ $inventory->nm_brg == 'Modem XPON' ? 'selected' : '' }}>Modem XPON</option>
                    <option value="Adaptor" {{ $inventory->nm_brg == 'Adaptor' ? 'selected' : '' }}>Adaptor</option>
                    <option value="Pathcore" {{ $inventory->nm_brg == 'Pathcore' ? 'selected' : '' }}>Pathcore</option>
                    <option value="Kebel 1 Core" {{ $inventory->nm_brg == 'Kebel 1 Core' ? 'selected' : '' }}>Kebel 1 Core
                    </option>
                    <option value="Kebel 2 Core" {{ $inventory->nm_brg == 'Kebel 2 Core' ? 'selected' : '' }}>Kebel 2 Core
                    </option>
                    <option value="Kebel 4 Core" {{ $inventory->nm_brg == 'Kebel 4 Core' ? 'selected' : '' }}>Kebel 4 Core
                    </option>
                    <option value="Kebel 6 Core" {{ $inventory->nm_brg == 'Kebel 6 Core' ? 'selected' : '' }}>Kebel 6 Core
                    </option>
                    <option value="Kebel 8 Core" {{ $inventory->nm_brg == 'Kebel 8 Core' ? 'selected' : '' }}>Kebel 8 Core
                    </option>
                    <option value="Kebel 12 Core" {{ $inventory->nm_brg == 'Kebel 12 Core' ? 'selected' : '' }}>Kebel 12
                        Core</option>
                    <option value="Kebel 24 Core" {{ $inventory->nm_brg == 'Kebel 24 Core' ? 'selected' : '' }}>Kebel 24
                        Core</option>
                    <option value="Kebel 48 Core" {{ $inventory->nm_brg == 'Kebel 48 Core' ? 'selected' : '' }}>Kebel 48
                        Core</option>
                    <option value="Rasio 1/2" {{ $inventory->nm_brg == 'Rasio 1/2' ? 'selected' : '' }}>Rasio 1/2</option>
                    <option value="Rasio 1/4" {{ $inventory->nm_brg == 'Rasio 1/4' ? 'selected' : '' }}>Rasio 1/4</option>
                    <option value="Rasio 1/6" {{ $inventory->nm_brg == 'Rasio 1/6' ? 'selected' : '' }}>Rasio 1/6</option>
                    <option value="Rasio 1/8" {{ $inventory->nm_brg == 'Rasio 1/8' ? 'selected' : '' }}>Rasio 1/8</option>
                    <option value="Rasio 1/12" {{ $inventory->nm_brg == 'Rasio 1/12' ? 'selected' : '' }}>Rasio 1/12
                    </option>
                    <option value="Splitter 01:99" {{ $inventory->nm_brg == 'Splitter 01:99' ? 'selected' : '' }}>Splitter
                        01:99</option>
                    <option value="Splitter 01:98" {{ $inventory->nm_brg == 'Splitter 01:98' ? 'selected' : '' }}>Splitter
                        01:98</option>
                    <option value="Splitter 01:97" {{ $inventory->nm_brg == 'Splitter 01:97' ? 'selected' : '' }}>Splitter
                        01:97</option>
                    <option value="Splitter 01:96" {{ $inventory->nm_brg == 'Splitter 01:96' ? 'selected' : '' }}>Splitter
                        01:96</option>
                    <option value="Splitter 01:95" {{ $inventory->nm_brg == 'Splitter 01:95' ? 'selected' : '' }}>Splitter
                        01:95</option>
                    <option value="Tang" {{ $inventory->nm_brg == 'Tang' ? 'selected' : '' }}>Tang</option>
                    <option value="Palu" {{ $inventory->nm_brg == 'Palu' ? 'selected' : '' }}>Palu</option>
                    <option value="Kabel Ties" {{ $inventory->nm_brg == 'Kabel Ties' ? 'selected' : '' }}>Kabel Ties
                    </option>
                    <option value="Lakban" {{ $inventory->nm_brg == 'Lakban' ? 'selected' : '' }}>Lakban</option>
                    <option value="Paku Klem" {{ $inventory->nm_brg == 'Paku Klem' ? 'selected' : '' }}>Paku Klem</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Jumlah</label>
                <input type="number" name="jml_brg" class="form-control" value="{{ $inventory->jml_brg }}" required>
            </div>
            <div class="mb-3">
                <label>Satuan</label>
                <select name="satuan" class="form-control" required>
                    <option value="">- Pilih -</option>
                    <option value="meter" {{ $inventory->satuan == 'meter' ? 'selected' : '' }}>Meter</option>
                    <option value="pcs" {{ $inventory->satuan == 'pcs' ? 'selected' : '' }}>Pcs</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Harga Satuan</label>
                <input type="number" step="0.01" name="harga_satuan" class="form-control"
                    value="{{ $inventory->harga_satuan }}" required>
            </div>
            <div class="mb-3">
                <label>Kategori</label>
                <select name="kategori" class="form-control" required>
                    <option value="">- Pilih -</option>
                    <option value="Modem" {{ $inventory->kategori == 'Modem' ? 'selected' : '' }}>Modem</option>
                    <option value="Kabel" {{ $inventory->kategori == 'Kabel' ? 'selected' : '' }}>Kabel</option>
                    <option value="Pathcore" {{ $inventory->kategori == 'Pathcore' ? 'selected' : '' }}>Pathcore</option>
                    <option value="Rasio" {{ $inventory->kategori == 'Rasio' ? 'selected' : '' }}>Rasio</option>
                    <option value="Splitter" {{ $inventory->kategori == 'Splitter' ? 'selected' : '' }}>Splitter</option>
                    <option value="Tang" {{ $inventory->kategori == 'Tang' ? 'selected' : '' }}>Tang</option>
                    <option value="Palu" {{ $inventory->kategori == 'Palu' ? 'selected' : '' }}>Palu</option>
                    <option value="Kabel Ties" {{ $inventory->kategori == 'Kabel Ties' ? 'selected' : '' }}>Kabel Ties
                    </option>
                    <option value="Lakban" {{ $inventory->kategori == 'Lakban' ? 'selected' : '' }}>Lakban</option>
                    <option value="Paku Klem" {{ $inventory->kategori == 'Paku Klem' ? 'selected' : '' }}>Paku Klem
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
@endsection
