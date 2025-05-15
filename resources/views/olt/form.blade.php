@extends($layout)

@section('konten')
    <div class="container py-5">
        <div class="row">
            <!-- Form Konfigurasi -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow rounded-4">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h4 class="mb-0">Configurasi OLT</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('olt.generate') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">SN Modem</label>
                                <input type="text" name="sn" class="form-control" value="{{ old('sn') }}" placeholder="Contoh: ZTEGCD818459"
                                    required>

                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Port</label>
                                    <input type="text" name="port" class="form-control" value="{{ old('port') }}" placeholder="1"
                                        required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Slot</label>
                                    <input type="text" name="slot" class="form-control" value="{{ old('slot') }}" placeholder="7"
                                        required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">PON</label>
                                    <input type="text" name="pon" class="form-control" value="{{ old('pon') }}" placeholder="2"
                                        required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nomor ONU</label>
                                <input type="number" name="number_onu" class="form-control" placeholder="65"
                                    value="{{ old('number_onu') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipe Modem</label>
                                <select name="type_modem" class="form-select" required>
                                    @foreach (['ALL', 'ZTE-F609', 'ZTE-F660', 'HG8245A', 'HG8245H', 'HG8546M', 'Fiberhome AN5506', 'TP-Link GPON'] as $modem)
                                        <option value="{{ $modem }}"
                                            {{ old('type_modem') == $modem ? 'selected' : '' }}>{{ $modem }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Modem</label>
                                <select name="nama_modem" class="form-select" required>
                                    <option value="asni" {{ old('nama_modem') == 'asni' ? 'selected' : '' }}>asni</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Profile TCONT</label>
                                <select name="tcont_profile" class="form-select">
                                    @foreach (['1G', '5M', '10M', '15M'] as $tcont)
                                        <option value="{{ $tcont }}"
                                            {{ old('tcont_profile') == $tcont ? 'selected' : '' }}>{{ $tcont }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">VLAN</label>
                                <input type="number" name="vlan" class="form-control" value="{{ old('vlan', 131) }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mode</label>
                                <select name="mode" id="mode" class="form-select">
                                    <option value="bridge" {{ old('mode') == 'bridge' ? 'selected' : '' }}>Bridge / non-ZTE
                                    </option>
                                    <option value="router" {{ old('mode') == 'router' ? 'selected' : '' }}>Router / ZTE
                                    </option>
                                </select>
                            </div>

                            <div id="pppoe" style="{{ old('mode') == 'router' ? 'display:block;' : 'display:none;' }}">
                                <div class="mb-3">
                                    <label class="form-label">PPPoE Username</label>
                                    <input type="text" name="pppoe_user" class="form-control"
                                        value="{{ old('pppoe_user') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">PPPoE Password</label>
                                    <input type="text" name="pppoe_pass" class="form-control"
                                        value="{{ old('pppoe_pass') }}">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Generate Script</button>
                        </form>

                    </div>
                </div>
            </div>

            <!-- Hasil Script -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow rounded-4">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h4 class="mb-0">Hasil Script Konfigurasi</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $script = session('script');
                        @endphp

                        @if ($script)
                            @php
                                $paragraphs = preg_split("/\n\s*\n/", $script);
                            @endphp

                            @foreach ($paragraphs as $index => $paragraf)
                                <div class="position-relative mb-4">
                                    <img src="{{ asset('asset/img/icon/copy.png') }}" class="copy-icon position-absolute"
                                        height="40px" data-target="script{{ $index }}"
                                        style="top: 10px; right: 10px; cursor: pointer;" title="Copy Script">
                                    <pre id="script{{ $index }}" class="bg-dark text-light p-3 rounded-3" style="min-height: 100px;">{{ $paragraf }}</pre>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">Silakan isi form dan klik generate untuk melihat script konfigurasi.</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        document.getElementById('mode').addEventListener('change', function() {
            document.getElementById('pppoe').style.display = this.value === 'router' ? 'block' : 'none';
        });

        document.querySelectorAll('.copy-icon').forEach(icon => {
            icon.addEventListener('click', function() {
                var targetId = this.getAttribute('data-target');
                var scriptText = document.getElementById(targetId).innerText;

                var tempTextArea = document.createElement('textarea');
                tempTextArea.value = scriptText;
                document.body.appendChild(tempTextArea);
                tempTextArea.select();
                document.execCommand('copy');
                document.body.removeChild(tempTextArea);

                alert('Script berhasil disalin!');
            });
        });

        document.getElementById('mode').addEventListener('change', function() {
            document.getElementById('pppoe').style.display = this.value === 'router' ? 'block' : 'none';
        });
    </script>


@endsection
