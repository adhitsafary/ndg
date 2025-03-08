@extends($layout)

@section('konten')
    <div class="container card">
        <h1>Tambah Barang</h1>
        <form action="{{ route('inventory.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nama Barang</label>
                <select name="nm_brg" class="form-control" required>
                    <option value="">- Pilih -</option>
                    <option value="Modem GPON">Modem GPON</option>
                    <option value="Modem EPON">Modem EPON</option>
                    <option value="Modem XPON">Modem XPON</option>
                    <option value="Adaptor">Adaptor</option>
                    <option value="Pathcore">Pathcore</option>
                    <option value="Kebel 1 Core">Kebel 1 Core</option>
                    <option value="Kebel 2 Core">Kebel 2 Core</option>
                    <option value="Kebel 4 Core<">Kebel 4 Core</option>
                    <option value="Kebel 6 Core<">Kebel 6 Core</option>
                    <option value="Kebel 8 Core">Kebel 8 Core</option>
                    <option value="Kebel 12 Core">Kebel 12 Core</option>
                    <option value="Kebel 24 Core">Kebel 24 Core</option>
                    <option value="Kebel 48 Core">Kebel 48 Core</option>
                    <option value="Rasio 1/2">Rasio 1/2</option>
                    <option value="Rasio 1/4">Rasio 1/4</option>
                    <option value="Rasio 1/6">Rasio 1/6</option>
                    <option value="Rasio 1/8">Rasio 1/8</option>
                    <option value="Rasio 1/12">Rasio 1/12</option>
                    <option value="Splitter 01:99">Splitter 01:99 </option>
                    <option value="Splitter 01:98 ">Splitter 01:98 </option>
                    <option value="Splitter 01:97 ">Splitter 01:97 </option>
                    <option value="Splitter 01:96 ">Splitter 01:96 </option>
                    <option value="Splitter 01:95 ">Splitter 01:95 </option>
                    <option value="Tang">Tang </option>
                    <option value="Palu">Palu </option>
                    <option value="Kabel Ties ">Kabel Ties </option>
                    <option value="Lakban ">Lakban </option>
                    <option value="Paku Klem ">Paku Klem </option>
                </select>
            </div>

            <div class="mb-3">
                <label>Jumlah</label>
                <input type="number" name="jml_brg" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Satuan</label>
                <select name="satuan" class="form-control" required>
                    <option value="">- Pilih -</option>
                    <option value="meter">Meter</option>
                    <option value="pcs">Pcs</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Harga Satuan</label>
                <input type="number" step="0.01" name="harga_satuan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Kategori</label>
                <select name="kategori" class="form-control" required>
                    <option value="">- Pilih -</option>
                    <option value="Modem">Modem</option>
                    <option value="Kabel">Kabel</option>
                    <option value="Pathcore">Pathcore</option>
                    <option value="Rasio">Rasio</option>
                    <option value="Splitter">Splitter</option>
                    <option value="Tang">Tang</option>
                    <option value="Palu">Palu</option>
                    <option value="Kabel Ties">Kabel Ties</option>
                    <option value="Lakban">Lakban</option>
                    <option value="Paku Klem ">Paku Klem </option>
                </select>
            </div>


            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>
@endsection
