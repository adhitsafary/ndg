@extends($layout)

@section('konten')
    <div class="container my-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Form Input Data OLT & Modem</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('olt.save.config') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">SN Modem</label>
                            <input type="text" name="sn_modem" class="form-control" placeholder="Contoh: ZTEGCD818459"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Port / Slot Card</label>
                            <input type="text" name="port_slot_card" class="form-control" placeholder="1/7/" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">SFP / PON</label>
                            <input type="text" name="sfp_pon" class="form-control" placeholder="Contoh: 3" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Number ONU</label>
                            <input type="number" name="number_onu" class="form-control" placeholder="Contoh: 65" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">VLAN</label>
                            <input type="number" name="vlan" class="form-control" value="131" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nama OLT</label>
                            <input type="text" name="nama_olt" class="form-control" value="Coba-NET" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Port / Slot Uplink</label>
                            <input type="text" name="port_slot_uplink" class="form-control" placeholder="Contoh: 1/4/"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Interface Uplink</label>
                            <input type="text" name="interface_uplink" class="form-control" placeholder="Contoh: 3"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">User OLT</label>
                            <input type="text" name="user_olt" class="form-control" value="zte" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password OLT</label>
                            <input type="text" name="password_olt" class="form-control" value="zte" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Privilege</label>
                            <input type="number" name="privilege" class="form-control" value="15" required>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-success">Simpan & Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
