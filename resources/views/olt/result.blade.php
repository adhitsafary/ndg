@extends($layout)

@section('konten')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow rounded-4">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h4 class="mb-0">Hasil Script Konfigurasi</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $paragraphs = preg_split("/\n\s*\n/", $script); // pisahkan per paragraf (baris kosong)
                        @endphp

                        @foreach ($paragraphs as $index => $paragraf)
                            <div class="position-relative mb-4">
                                <img src="{{ asset('asset/img/icon/copy.png') }}" class="copy-icon position-absolute"
                                    height="40px" data-target="script{{ $index }}"
                                    style="top: 10px; right: 10px; cursor: pointer;" title="Copy Script">
                                <pre id="script{{ $index }}" class="bg-dark text-light p-3 rounded-3" style="min-height: 100px;">{{ $paragraf }}</pre>
                            </div>
                        @endforeach

                        <a href="{{ route('olt.form.detail') }}" class="btn btn-outline-secondary mt-3">← Kembali ke Form</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script JS untuk salin -->
    <script>
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
    </script>
@endsection
