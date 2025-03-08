@extends($layout)

@section('konten')
    <div class="container mt-4 card">
        <h6 class="text text-center text-black mt-3"> Edit Data rekap pemasangan : {{ $rekap_pemasangan->nama }} </h6>
        <form action="{{ route('rekap_pemasangan.update', $rekap_pemasangan->id) }}" method="POST">
            @csrf

            <label for="">ID Pelanggan</label>
            <input type="text" name="id_plg" value="{{ $rekap_pemasangan->id_plg }}" class="form-control mb-3">

            <label for="">NIK</label>
            <input type="text" name="nik" value="{{ $rekap_pemasangan->nik }}" class="form-control mb-3">

            <label for="">Nama</label>
            <input type="text" name="nama" value="{{ $rekap_pemasangan->nama }}" class="form-control mb-3">

            <label for="">Alamat</label>
            <input type="text" name="alamat" value="{{ $rekap_pemasangan->alamat }}" class="form-control mb-3">

            <label for="">No Telepon</label>
            <input type="text" name="no_telpon" value="{{ $rekap_pemasangan->no_telpon }}" class="form-control mb-3">

            <label for="">Aktivasi</label>
            <input type="date" name="tgl_aktivasi" value="{{ $rekap_pemasangan->tgl_aktivasi }}"
                class="form-control mb-3">

            <label for="">Paket</label>
            <input type="text" name="paket_plg" value="{{ $rekap_pemasangan->paket_plg }}" class="form-control mb-3">

            <label for="">Harga Paket</label>
            <input type="text" name="harga_paket" value="{{ $rekap_pemasangan->harga_paket }}" class="form-control mb-3">

            <label for="">Jatuh Tempo</label>
            <input type="text" name="jt" value="{{ $rekap_pemasangan->jt }}" class="form-control mb-3">

            <label for="">Status</label>
            <input type="text" name="status" value="{{ $rekap_pemasangan->status }}" class="form-control mb-3">

            <label for="">Pengajuan</label>
            <input type="date" name="tgl_pengajuan" value="{{ $rekap_pemasangan->tgl_pengajuan }}"
                class="form-control mb-3">

            <label for="">Registrasi</label>
            <input type="text" name="registrasi" value="{{ $rekap_pemasangan->registrasi }}" class="form-control mb-3">

            <label for="">Marketing</label>
            <input type="text" name="marketing" value="{{ $rekap_pemasangan->marketing }}" class="form-control mb-3">

            <br>
            <label for="">SN Modem Awal</label>
            <input type="text" id="sn_modem_awal" class="form-control" value="{{ $rekap_pemasangan->sn_modem }}"
                readonly>

            <label for="sn_modem">Modem :</label>
            <select name="sn_modem" id="sn_modem" style="width: 100%;">
                <option value="" {{ $rekap_pemasangan->sn_modem == null ? 'selected' : '' }}>Tidak Pilih Modem
                </option>
                @foreach ($modems as $modem)
                    <option value="{{ $modem->sn_modem }}"
                        {{ $rekap_pemasangan->sn_modem == $modem->sn_modem ? 'selected' : '' }}>
                        {{ $modem->sn_modem }} - {{ $modem->model }}
                    </option>
                @endforeach
            </select>




            <div class="form-group mt-4">
                <label for="teknisi">Pilih Teknisi :</label>
                <div>
                    @php
                        $teknisiTerpilih = explode(',', $rekap_pemasangan->teknisi); // Konversi string ke array
                    @endphp
                    @php
                        // Pecah string menjadi array dan hilangkan spasi di awal & akhir setiap elemen
                        $teknisiTerpilih = array_map('trim', explode(',', $rekap_pemasangan->teknisi));
                    @endphp

                    @foreach (['Deden', 'Agisdut', 'Dindin', 'Mursidi', 'Isep', 'Indra', 'Adit', 'Johan', 'Gilang'] as $teknisi)
                        <input type="checkbox" name="teknisi[]" value="{{ $teknisi }}"
                            {{ in_array($teknisi, $teknisiTerpilih) ? 'checked' : '' }}>
                        {{ $teknisi }}<br>
                    @endforeach

                </div>
            </div>

            <button class="btn btn-primary btn-sm">Simpan</button> <br><br>
        </form>
    </div>
    //

    <script>
        $(document).ready(function() {
            $('#sn_modem').select2({
                placeholder: "Cari dan Pilih Modem",
                allowClear: true
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('#sn_modem').select2({
                placeholder: "Cari dan Pilih Modem",
                allowClear: true
            });

            // Update SN Modem Awal ketika pilihan berubah
            $('#sn_modem').on('change', function() {
                let selectedModem = $(this).val();
                $('#sn_modem_awal').val(selectedModem ? selectedModem : ''); // Kosongkan jika tidak pilih
            });
        });
    </script>
@endsection
