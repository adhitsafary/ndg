<label for="" class=" mt-2">Nama Peserta</label>
<select name="nama" id="nama" class="form-control" onchange="setHargaPaket()" required>
    <option value="" disabled selected>Pilih Nama</option>
    <option value="Nada">Nada</option>
    <option value="Lia">Lia</option>
    <option value="Ilyas">Ilyas</option>
    <option value="Intan">Intan</option>
    <option value="Sinta">Sinta</option>
    <option value="Anggi">Anggi</option>
</select>
<div class="form-group">
    <label>Judul</label>
    <input type="text" name="judul" class="form-control" value="{{ old('judul', $data->judul ?? '') }}" required>
</div>
<div class="form-group">
    <label>Tanggal</label>
    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $data->tanggal ?? '') }}"
        required>
</div>
<div class="form-group">
    <label>Dekripsi</label>
    <textarea name="dekripsi" class="form-control" required>{{ old('dekripsi', $data->dekripsi ?? '') }}</textarea>
</div>
<div class="form-group">
    <label>Foto</label>
    <input type="file" name="foto" class="form-control">
    @if (!empty($data->foto))
        <img src="{{ asset('storage/' . $data->foto) }}" width="100" class="mt-2">
    @endif
</div>
