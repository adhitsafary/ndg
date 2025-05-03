@extends($layout)

@section('konten')
    <style>
        .card-style {
            border-radius: 20px;
            color: white;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            min-height: 120px;
        }

        .card-style:hover {
            transform: scale(1.02);
            opacity: 0.95;
        }

        .cash-card {
            background: linear-gradient(135deg, #00c4ff, #0096c7);
        }

        .tf-card {
            background: linear-gradient(135deg, #8e8e8e, #6c757d);
        }

        .pengeluaran-card {
            background: linear-gradient(135deg, #ff6b00, #e85d04);
        }

        .pemasukan-card {
            background: linear-gradient(135deg, #b80000, #ff0033);
        }

        .summary-card {
            background: linear-gradient(to right, #00bcd4, #007bb2);
            border-radius: 20px;
            padding: 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .summary-card>div {
            flex: 1;
            text-align: center;
        }

        .summary-card h1 {
            font-size: 2.5rem;
            margin-bottom: 5px;
        }

        .sub-card {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 10px;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .payment-box {
            background: linear-gradient(to right, #00bcd4, #007bb2);
            border-radius: 20px;
            padding: 30px;
            color: white;
            margin-top: 30px;
        }

        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .filter-form input {
            display: inline-block;
            width: auto;
        }

        .filter-form .btn {
            margin-left: 10px;
        }

        .card-group {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
    </style>

    <div class=" m-4 card">
        <div class="filter-header">

            <form action="{{ route('rekap-harian') }}" method="GET" class="filter-form form-inline">
                <input type="date" name="tanggal" value="{{ $tanggalHariIni }}" class="form-control">
                <button class="btn btn-dark">Filter</button>
            </form>
        </div>

        <div class="summary-card shadow">
            <div>
                <div>Pendapatan Hari Ini</div>
                <h1>Rp. {{ number_format($totalPendapatanHarian) }}</h1>
            </div>
            <div>
                <div>Pengeluaran</div>
                <h1>Rp. {{ number_format($totalPengeluaran) }}</h1>
            </div>
            <div>
                <div>Neto</div>
                <h1>Rp. {{ number_format($totalsaldo) }}</h1>
            </div>
        </div>

        <div class="card-group mb-4">
            <div class="card-style cash-card flex-fill" onclick="toggleDetail('cash-detail')">
                <strong>Cash Rp. {{ number_format($cashTagihan + $cashPiutang + $cashPsb) }}</strong>
                <div id="cash-detail" class="sub-card d-none">
                    Tagihan: Rp. {{ number_format($cashTagihan) }}<br>
                    Piutang: Rp. {{ number_format($cashPiutang) }}<br>
                    PSB: Rp. {{ number_format($cashPsb) }}
                </div>
            </div>
            <div class="card-style tf-card flex-fill" onclick="toggleDetail('tf-detail')">
                <strong>TF Rp. {{ number_format($tfTagihan + $tfPiutang + $tfPsb) }}</strong>
                <div id="tf-detail" class="sub-card d-none">
                    Tagihan: Rp. {{ number_format($tfTagihan) }}<br>
                    Piutang: Rp. {{ number_format($tfPiutang) }}<br>
                    PSB: Rp. {{ number_format($tfPsb) }}
                </div>
            </div>
            <div class="card-style pengeluaran-card flex-fill" onclick="toggleDetail('pengeluaran-detail')">
                <strong>Pengeluaran Rp. {{ number_format($totalPengeluaran) }}</strong>
                <div id="pengeluaran-detail" class="sub-card d-none">
                    @foreach ($listPengeluaran as $p)
                        {{ $p->deskripsi }}: Rp. {{ number_format($p->harga_total) }}<br>
                    @endforeach
                </div>
            </div>

            <div class="card-style pemasukan-card flex-fill" onclick="toggleDetail('pemasukan-detail')">
                <strong>Pemasukan Rp. {{ number_format($totalPemasukan) }}</strong>
                <div id="pemasukan-detail" class="sub-card d-none">
                    @foreach ($listPemasukan as $p)
                        {{ $p->deskripsi }}: Rp. {{ number_format($p->harga_total) }}<br>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="payment-box shadow">
            <h5 class="mb-4">Pembayaran Hari Ini ({{ $totalUserHarian }} User)</h5>
            <table class="table table-striped table-hover bg-white text-dark rounded">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Pembayaran</th>
                        <th>Kategori</th>
                        <th>Untuk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembayaranHarian as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->nama_plg }}</td>
                            <td>Rp. {{ number_format($p->jumlah_pembayaran) }}</td>
                            <td>{{ $p->metode_transaksi }}</td>
                            <td>{{ $p->untuk_pembayaran }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada pembayaran hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleDetail(id) {
            document.getElementById(id).classList.toggle('d-none');
        }
    </script>
@endsection
