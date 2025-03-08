@extends($layout)

@section('konten')
    <div class="card m-5">
        @if (session('error'))
            <div class="alert alert-danger" style="background: #a72828; color: white; border: 1px solid #ff0000;">
                {{ session('error') }}
            </div>
        @endif

        @if (session('alert'))
            <div class="alert  alert-dismissible fade show" role="alert"
                style="background: #a72828; color: white; border: 1px solid #ff0000;">
                {{ session('alert') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-dismissible fade show" role="alert"
                style="background: #28a745; color: white; border: 1px solid #218838;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Modal Loading -->
        <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Sedang Memproses...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Sukses -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="successModalLabel">
                            <span class="me-2">✅</span> Berhasil!
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <h3 class="text-success">✔</h3> <!-- Ikon besar -->
                        <p id="successMessage" class="mt-2"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Gagal -->
        <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="errorModalLabel">
                            <span class="me-2">❌</span> Gagal!
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <h3 class="text-danger">✖</h3> <!-- Ikon besar -->
                        <p id="errorMessage" class="mt-2"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <h4 style="color: black">Riwayat Pembayaran - {{ $pelanggan->nama_plg }}</h4>
        <table class="table table-bordered table-responsive mt-3" style="color: black">
            <thead class="table table-primary" style="color: black">
                <tr class="">
                    <th>No</th>
                    <th>ID PEL</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>Metode Pembayaran</th>
                    <th>Tanggal Tagih</th>
                    <th>Paket</th>
                    <th>Jumlah Pembayaran</th>
                    <th>Tanggal Pembayaran</th>
                    <th>Untuk Bulan</th>
                    <th>Keterangan Pembayaran</th>
                    <th>Admin</th>
                    <th>Edit</th>
                    <th>Hapus</th>
                    <th>Print</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembayaran as $no => $bayar)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $bayar->id_plg }}</td>
                        <td>{{ $bayar->nama_plg }}</td>
                        <td>{{ $bayar->alamat_plg }}</td>
                        <td>{{ $bayar->metode_transaksi }}</td>
                        <td>{{ $bayar->tgl_tagih_plg }}</td>
                        <td>{{ $bayar->paket_plg }}</td>
                        <td>{{ number_format($bayar->jumlah_pembayaran, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($bayar->created_at)->locale('id')->translatedFormat('l, d F Y H:i:s') }}
                        </td>
                        <td>{{ $bayar->tanggal_pembayaran }}</td>
                        <td>{{ $bayar->keterangan_plg }}</td>
                        <td>{{ $bayar->admin_name }}</td>
                        <td>
                            <a href="{{ route('pembayaran.edit', $bayar->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                        <td>
                            <form action="{{ route('detail_plg.destroy', $bayar->id) }}" method="POST"
                                class="d-inline-block">
                                @csrf

                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm"
                                onclick="printPayment({{ $no + 1 }}, '{{ $bayar->nama_plg }}')">Print</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="/pelanggan" class="btn btn-primary">
            << Kembali</a>
    </div>

    <!-- QRCode.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        function printPayment(rowNumber, namaPelanggan) {
            // Get data from the row
            var row = document.querySelectorAll('table tbody tr')[rowNumber - 1];
            var id = row.cells[1].innerText;
            var nama = row.cells[2].innerText;
            var alamat = row.cells[3].innerText;
            var metode_transaksi = row.cells[4].innerText;
            var tglBayar = row.cells[8].innerText;
            var jumlahBayar = row.cells[7].innerText;

            // Create the print content resembling the DANA receipt format with additional logo and colors
            var printContent = `
              <div style="border: 1px solid #000; padding: 20px; width: 350px; margin: 0 auto; font-family: Arial, sans-serif;">
    <!-- Bagian Header -->
    <div style="border-bottom: 2px solid #000; text-align: center; padding-bottom: 10px; margin-bottom: 10px;">
        <h2 style="font-size: 16px; margin: 0;">KWITANSI PEMBAYARAN INTERNET BULANAN</h2>
    </div>

    <!-- Bagian untuk logo dan teks "Net Digital Group Digital" -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <!-- Logo di sebelah kiri -->
        <div style="flex: 1; text-align: left;">
            <img src="{{ asset('asset/img/Net Digital Group.jpg') }}" style="height: 30px; width: 30px;">
        </div>

        <!-- Teks "Net Digital Group Digital" di tengah -->
        <div style="flex: 2; text-align: center;">
            <h2 style="font-size: 16px; margin: 0;">Net Digital Group Digital</h2>
        </div>

        <!-- Logo di sebelah kanan -->
        <div style="flex: 1; text-align: right;">
            <img src="{{ asset('asset/img/logo_hayat.png') }}" style="height: 30px; width: 60px;">
        </div>
    </div>

    <!-- Informasi pelanggan -->
    <p><strong>ID Pelanggan:</strong> ${id}</p>
    <p><strong>Nama Pelanggan:</strong> ${nama}</p>
    <p><strong>Alamat:</strong> ${alamat}</p>
    <p><strong>Metode Pembayaran:</strong> ${metode_transaksi}</p>
    <p><strong>Tanggal Pembayaran:</strong> ${tglBayar}</p>
    <p><strong>Jumlah Pembayaran:</strong> Rp ${jumlahBayar}</p>

    <!-- QR Code -->
    <div class="text-center" id="qrcode" style="text-align: center; margin: 20px 0;"></div>

    <!-- Ucapan terima kasih -->
    <div style="text-align: center; font-size: 12px;">
        <p>Terima kasih telah melakukan pembayaran</p>
        <p>--- Net Digital Group Digital ---</p>
    </div>
</div>

            `;

            // Open new window for printing
            var printWindow = window.open('', '', 'height=600,width=400');
            printWindow.document.write('<html><head><title>Struk Pembayaran</title>');
            printWindow.document.write('<style>body { font-family: Arial, sans-serif; }</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(printContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Wait for the window to load
            printWindow.onload = function() {
                // Print after loading
                printWindow.print();
            }
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Tampilkan modal sukses jika ada session success
            @if (session('success'))
                document.getElementById("successMessage").innerText = "✅ {{ session('success') }}";
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            @endif

            // Tampilkan modal gagal jika ada session error
            @if (session('error'))
                document.getElementById("errorMessage").innerText = "❌ {{ session('error') }}";
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            @endif
        });

        document.getElementById("bayarForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Mencegah form langsung submit

            var loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
            loadingModal.show(); // Tampilkan modal loading

            // Simulasi proses pembayaran (ganti dengan AJAX jika perlu)
            setTimeout(function() {
                loadingModal.hide(); // Sembunyikan modal loading

                // Simulasi sukses atau gagal (Gantilah dengan kondisi nyata dari server)
                var isSuccess = Math.random() > 0.3; // 70% sukses, 30% gagal

                if (isSuccess) {
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    document.getElementById('successMessage').innerText = "Pembayaran berhasil!";
                    successModal.show();

                    // Submit form setelah sukses (atau panggil API jika pakai AJAX)
                    document.getElementById("bayarForm").submit();
                } else {
                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    document.getElementById('errorMessage').innerText =
                        "Pembayaran gagal! Silakan coba lagi.";
                    errorModal.show();
                }
            }, 3000); // Simulasi proses selama 3 detik
        });
    </script>
@endsection
