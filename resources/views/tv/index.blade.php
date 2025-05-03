@extends('layout_tv_baru')

@section('konten')
    <div class="container-fluid" id="container-wrapper" >
        <br><br>

        <div class="row mb-3 ml-2 mr-2 ">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0 rounded-lg"
                    style="background: linear-gradient(135deg, #c00000, #ff8efd); color: white;">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                            style="width: 50px; height: 50px;">
                            <img src="{{ asset('asset/img/icon/pelanggan2.png') }}" height="30px">
                        </div>
                        <div>
                            <div class="font-weight-semibold mb-1">Total Tagihan Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold">Rp
                                {{ number_format($total_jml_pembayaran_harian, 0, ',', '.') }}</div>
                            <small><i class="fas fa-users"></i> {{ $total_plg_pembayaran_harian }} pelanggan</small>
                        </div>
                    </div>
                </div>
            </div>




            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0 rounded-lg"
                    style="background: linear-gradient(135deg, #28a745, #b8d05f); color: white;">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                            style="width: 50px; height: 50px;">
                            <img src="{{ asset('asset/img/icon/uang_karung.png') }}" height="30px">
                        </div>
                        <div>
                            <div class="font-weight-semibold mb-1">Tagihan Terbayar Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold">Rp {{ number_format($total_bayar_harian, 0, ',', '.') }}
                            </div>
                            <small><i class="fas fa-user-check"></i> {{ $total_user_bayar_harian }} user</small>
                        </div>
                    </div>
                </div>
            </div>




            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0 rounded-lg"
                    style="background: linear-gradient(135deg, #dc3545, #d4d800); color: white;">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                            style="width: 50px; height: 50px;">
                            <img src="{{ asset('asset/img/icon/sisa.png') }}" height="30px">
                        </div>
                        <div>
                            <div class="font-weight-semibold mb-1">Sisa Tagihan Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold">Rp
                                {{ number_format($belum_sisa_bayar_harian, 0, ',', '.') }}</div>
                            <small><i class="fas fa-user-check"></i> {{ $total_user_sisa_harian }} user</small>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0 rounded-lg"
                    style="background: linear-gradient(135deg, #6f42c1, #e83e8c); color: white;">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                            style="width: 50px; height: 50px;">
                            <img src="{{ asset('asset/img/icon/uang_karung.png') }}" height="30px">
                        </div>
                        <div>
                            <div class="font-weight-semibold mb-1">Tagihan Terbayar (Persentase)</div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ number_format(($total_bayar_harian / $total_jml_pembayaran_harian) * 100, 2, ',', '.') }}%
                            </div>
                            <small><i class="fas fa-user-check"></i> {{ $total_user_bayar_harian }} user</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Bar dan Line -->
            <!-- DISSEMBUNYIKAN tapi tetap ada di DOM -->
            <div class="col-xl-70 col-lg-8 mb-3 d-none">
                <div class="card-biru_tua" style="font-size: 1.5rem; height: 500px;">
                    <div class="card-body" style="height: 100%;">
                        <h6 class="text text-white font-weight-bold">Tabel Pembayaran Per Bulan</h6>
                        <div class="chart-area" style="height: 400px;">
                            <canvas id="pendapatanChart" width="850" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card-biru_tua col-xl-4 col-lg- mt-0 h-100 mb-3 d-none ">
                <div class="p-3 ">
                    <div class="py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 pl-3 font-weight-bold text-white">PERSANTE PEMBAYARAN</h6>
                    </div>
                    <div class="chart-area">
                        <canvas id="myPieChart"></canvas> <!-- Untuk Pie Chart -->
                    </div>
                    <!-- Row untuk Baru Terbayar dan Total Tagihan -->
                    <div class="mt-3 ml-2 d-flex justify-content-between">

                        <!-- Total Tagihan -->
                        <div class=" card bg-success py-3 d-flex flex-column align-items-start justify-content-center"
                            style="width: 48%;">
                            <h6 class="m-0 pl-3 font-weight-bold text-white">Total Tagihan</h6>
                            <div class="text-white h6 mb-0 font-weight-bold pl-3">
                                Rp {{ number_format($totalTagihanHariIni, 0, ',', '.') }} <br> User :
                                {{ $jumlahPelangganMembayarHariIni }}
                            </div>
                        </div>
                        <!-- Baru Terbayar -->
                        <div class="card bg-warning py-3 d-flex flex-column align-items-start justify-content-center ml-2"
                            style="width: 48%; margin-right: 10px;">
                            <h6 class="m-0 pl-3 font-weight-bold text-white">Tertagih</h6>
                            <div class="text-white h6 mb-0 font-weight-bold pl-3">
                                Rp {{ number_format($totalPendapatanharian_semua, 0, ',', '.') }} <br> User :
                                {{ $totalUserHarian_semua }}
                            </div>
                        </div>
                        <!--sisa tagihan-->
                        <div class="card bg-danger py-3 d-flex flex-column align-items-start justify-content-center mr-2"
                            style="width: 48%;">
                            <h6 class="m-0 pl-3 font-weight-bold text-white">Sisa Tagihan</h6>
                            <div class="text-white h6 mb-0 font-weight-bold pl-3">
                                Rp {{ number_format($totalTagihanTertagih, 0, ',', '.') }} <br> User :
                                {{ $totalUserTertagih }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-lg ">
                <div class="card shadow-sm border-0 rounded-lg"
                    style="background: linear-gradient(135deg, #00c6ff, #0072ff); color: white;">
                    <div class="card-body d-flex flex-column" style="height: 100%;">
                        <h5 class="font-weight-bold mb-3 d-flex align-items-center">
                            <i class="fas fa-receipt mr-2"></i> Pelanggan Bayar Terbaru
                        </h5>

                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm table-hover text-white mb-0" style="font-size: 13px;">
                                <thead class="thead-light text-primary bg-white">
                                    <tr class="text-center">
                                        <th style="width: 40%;">Nama Pelanggan</th>
                                        <th style="width: 30%;">Harga Paket</th>
                                        <th style="width: 30%;">Tanggal Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($query_pelanggans as $item)
                                        <tr class="text-center">
                                            <td>{{ $item->nama_plg }}</td>
                                            <td>Rp {{ number_format($item->harga_paket, 0, ',', '.') }}</td>
                                            <td>{{ $item->updated_at->format('d-m-Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($query_pelanggans->isEmpty())
                            <div class="text-center text-white mt-3">
                                <i class="fas fa-info-circle"></i> Belum ada pembayaran hari ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>


            <style>
                /* Mengecilkan font dan padding tabel */
                .table {
                    font-size: 12px;
                }

                .table th,
                .table td {
                    padding: 5px;
                    text-align: center;
                }

                .custom-cell {
                    padding: 10px;
                    text-align: center;
                    font-size: 1.0em;
                    font-weight: bold;
                    cursor: pointer;
                    color: white;
                }

                .custom-cell.head {
                    background: #530096;
                    /* Biru */
                }

                .custom-cell.info {
                    background: #17a2b8;
                    /* Biru */
                }

                .custom-cell.warning {
                    background: #ffc107;
                    /* Kuning */
                    color: black;
                }

                .custom-cell.danger {
                    background: #dc3545;
                    /* Merah */
                }

                .custom-cell.success {
                    background: #28a745;
                    /* Hijau */
                }

                .custom-cell.primary {
                    background: #007bff;
                    /* Biru tua */
                }

                .custom-cell.primary-yellow {
                    background: #ecc100;
                    /* Kuning terang */
                    color: black;
                }

                .custom-cell.primary-red {
                    background: #ff0000;
                    /* Merah terang */
                }

                .custom-cell.primary-green {
                    background: rgb(32, 190, 0);
                    /* Hijau terang */
                }

                .table-bordered {
                    border: 1px solid #dee2e6;
                    width: 100%;
                }

                .table th,
                .table td {
                    border: 1px solid #dee2e6;
                    vertical-align: middle;
                }

                .table {
                    width: 100%;
                    table-layout: fixed;
                    /* Membuat lebar kolom rata */
                }

                a {
                    color: white;
                    text-decoration: none;
                }

                a:hover {
                    text-decoration: underline;
                }
            </style>




            <canvas id="pendapatanChart" height="120"></canvas>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                const labels = @json($labels); // ['00:00', '01:00', ..., '23:00']
                const totalPembayaran = @json($totalPembayaran);

                const ctx = document.getElementById('pendapatanChart').getContext('2d');
                const pendapatanChart = new Chart(ctx, {
                    type: 'line', // Gunakan line chart agar bisa pakai fill (ombak)
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Pembayaran (Rp)',
                            data: totalPembayaran,
                            fill: true, // Aktifkan efek ombak
                            backgroundColor: 'rgba(255, 157, 0, 0.2)', // Ombak
                            borderColor: 'rgb(255, 157, 0)', // Garis utama
                            borderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.3 // Buat garis agak melengkung
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                type: 'category', // Pastikan bukan time
                                title: {
                                    display: true,
                                    text: 'Jam',
                                    color: 'white',
                                    font: {
                                        weight: 'bold'
                                    }
                                },
                                ticks: {
                                    color: 'white'
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.2)'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Total Pembayaran (Rp)',
                                    color: 'white',
                                    font: {
                                        weight: 'bold'
                                    }
                                },
                                ticks: {
                                    color: 'white'
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.2)'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: 'white',
                                    font: {
                                        style: 'bold'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                titleColor: 'black',
                                bodyColor: 'black',
                                borderColor: 'rgba(255, 157, 0, 1)',
                                borderWidth: 1
                            }
                        }
                    }
                });



                // Data untuk Pie Chart
                var totalTagihanHariIni = @json($totalTagihanHariIni); // Total tagihan hari ini
                var totalPendapatanharian_semua = @json($totalPendapatanharian_semua); // Total pendapatan harian semua

                // Inisialisasi Pie Chart
                var ctx2 = document.getElementById("myPieChart").getContext('2d');
                var myPieChart = new Chart(ctx2, {
                    type: 'pie', // Menggunakan tipe pie untuk lingkaran penuh
                    data: {
                        datasets: [{
                            data: [totalTagihanHariIni - totalPendapatanharian_semua,
                                totalPendapatanharian_semua
                            ], // Data dari controller
                            backgroundColor: ['#e74c3c', '#FFBB00'], // Warna untuk bagian chart
                            hoverBackgroundColor: ['#c0392b', '#FFBB00'], // Warna saat di-hover
                            hoverBorderColor: "rgba(234, 236, 244, 1)", // Border saat di-hover
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            titleFontColor: "white", // Judul tooltip putih
                            bodyFontColor: "white", // Isi tooltip putih
                            titleFontStyle: "bold", // Judul tooltip bold
                            bodyFontStyle: "bold", // Isi tooltip bold
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var dataset = data.datasets[tooltipItem.datasetIndex];
                                    var total = dataset.data.reduce(function(previousValue, currentValue) {
                                        return previousValue + currentValue;
                                    });
                                    var currentValue = dataset.data[tooltipItem.index];
                                    var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
                                    return data.labels[tooltipItem.index] + ': ' + percentage + '%';
                                }
                            }
                        },
                        legend: {
                            display: true, // Tampilkan legenda untuk menjelaskan chart
                            position: 'bottom', // Posisi legenda di bawah chart
                            labels: {
                                fontColor: "white", // Warna teks legenda menjadi putih
                                fontStyle: "bold", // Teks legenda menjadi bold
                                usePointStyle: true // Menjaga ikon lingkaran di legend
                            }
                        },
                        cutoutPercentage: 0, // Tidak ada ruang di tengah lingkaran (untuk pie chart penuh)
                        plugins: {
                            labels: {
                                render: 'label',
                                fontColor: 'white', // Membuat label chart menjadi putih
                                fontStyle: 'bold' // Membuat teks label chart menjadi bold
                            }
                        }
                    }
                });



                //Target Marketing
                // Data untuk Pie Chart
                var sisa_target = @json($sisa_target); // Total tagihan hari ini
                var jumlah_target = @json($jumlah_target); // Total pendapatan harian semua

                // Inisialisasi Pie Chart
                var ctx2 = document.getElementById("myPieChart1").getContext('2d');
                var myPieChart1 = new Chart(ctx2, {
                    type: 'pie', // Menggunakan tipe pie untuk lingkaran penuh
                    data: {
                        datasets: [{
                            data: [sisa_target - jumlah_target,
                                jumlah_target
                            ], // Data dari controller
                            backgroundColor: ['#00BFFF', '#0000CD '], // Warna untuk bagian chart
                            hoverBackgroundColor: ['#00BFFF', '#0000CD '], // Warna saat di-hover
                            hoverBorderColor: "rgba(234, 236, 244, 1)", // Border saat di-hover
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            titleFontColor: "white", // Judul tooltip putih
                            bodyFontColor: "white", // Isi tooltip putih
                            titleFontStyle: "bold", // Judul tooltip bold
                            bodyFontStyle: "bold", // Isi tooltip bold
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var dataset = data.datasets[tooltipItem.datasetIndex];
                                    var total = dataset.data.reduce(function(previousValue, currentValue) {
                                        return previousValue + currentValue;
                                    });
                                    var currentValue = dataset.data[tooltipItem.index];
                                    var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
                                    return data.labels[tooltipItem.index] + ': ' + percentage + '%';
                                }
                            }
                        },
                        legend: {
                            display: true, // Tampilkan legenda untuk menjelaskan chart
                            position: 'bottom', // Posisi legenda di bawah chart
                            labels: {
                                fontColor: "white", // Warna teks legenda menjadi putih
                                fontStyle: "bold", // Teks legenda menjadi bold
                                usePointStyle: true // Menjaga ikon lingkaran di legend
                            }
                        },
                        cutoutPercentage: 0, // Tidak ada ruang di tengah lingkaran (untuk pie chart penuh)
                        plugins: {
                            labels: {
                                render: 'label',
                                fontColor: 'white', // Membuat label chart menjadi putih
                                fontStyle: 'bold' // Membuat teks label chart menjadi bold
                            }
                        }
                    }
                });
            </script>




        </div>




        <!-- Footer -->
        <br><br>
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>copyright &copy;
                        <script>
                            document.write(new Date().getFullYear());
                        </script> - developed by
                        <b><a href="" target="_blank">Net Digital Group</a></b>
                    </span>
                </div>
            </div>
        </footer>
        </footer>
    @endsection


    <!-- CSS untuk Running Text -->
    <style>
        .running-text-container {
            background-color: rgb(48, 48, 48);
            /* Mengubah latar belakang menjadi hitam */
            color: white;
            /* Mengubah warna teks menjadi putih untuk kontras yang baik */
            overflow: hidden;
            white-space: nowrap;
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            height: 50px;
            /* Tinggi kontainer */
        }

        .running-text {
            display: inline-block;
            font-size: 1.2rem;
            /* Sedikit memperbesar ukuran font */
            animation: scroll-left 20s linear infinite;
            /* Durasi animasi tetap */
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }
    </style>



    <script>
        // Auto-refresh halaman setiap 30 detik
        setInterval(() => {
            location.reload(); // Reload halaman
        }, 100000); // 30.000 ms = 30 detik
    </script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            const labels = @json($labels); // Label untuk tanggal (1-30)
            const totalPemasangan = @json($totalPemasangan); // Jumlah pemasangan per tanggal
            const totalPendapatan = @json($totalPendapatan); // Total pendapatan per tanggal

            // Inisialisasi Bar dan Line Chart
            const ctx2 = document.getElementById('pendapatanChart_PSB').getContext('2d');
            const pendapatanChartPSB = new Chart(ctx2, {
                type: 'bar', // Tipe chart batang (bar)
                data: {
                    labels: labels, // Label (Tanggal 1-30)
                    datasets: [{
                            label: 'Total Pemasangan',
                            data: totalPemasangan, // Data jumlah pemasangan per hari
                            backgroundColor: 'rgba(255, 255, 255, 0.2)', // Warna batang putih semi transparan
                            borderColor: 'rgb(255, 157, 0)', // Warna border batang oranye
                            borderWidth: 1,
                            pointRadius: 8, // Ukuran titik lebih besar
                            pointHoverRadius: 10 // Ukuran titik saat di-hover lebih besar
                        },
                        {
                            label: 'Total Pendapatan (Rp)',
                            data: totalPendapatan, // Data total pendapatan per hari
                            backgroundColor: 'rgba(255, 255, 255, 0.2)', // Warna grafik garis putih semi transparan
                            borderColor: 'rgb(255, 157, 0)', // Warna border garis oranye
                            borderWidth: 1,
                            type: 'line', // Grafik tipe garis (line)
                            pointRadius: 8, // Ukuran titik untuk garis lebih besar
                            pointHoverRadius: 10 // Ukuran titik saat di-hover lebih besar
                        }
                    ]
                },
                options: {
                    layout: {
                        padding: 0 // Menghilangkan padding pada layout
                    },
                    scales: {
                        y: {
                            beginAtZero: true, // Memulai grafik dari 0 pada sumbu Y
                            ticks: {
                                color: 'white' // Warna label sumbu Y menjadi putih
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.2)' // Warna grid sumbu Y putih semi transparan
                            }
                        },
                        x: {
                            ticks: {
                                color: 'white' // Warna label sumbu X menjadi putih
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.2)' // Warna grid sumbu X putih semi transparan
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white', // Warna label legenda menjadi putih
                                font: {
                                    style: 'bold' // Membuat label legenda bold
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.8)', // Background tooltip putih semi transparan
                            titleColor: 'black', // Warna judul tooltip hitam
                            bodyColor: 'black', // Warna isi tooltip hitam
                            borderColor: 'rgba(255, 255, 255, 1)', // Warna border tooltip putih
                            borderWidth: 1,
                            titleFont: {
                                weight: 'bold' // Membuat teks judul tooltip bold
                            }
                        }
                    }
                }
            });
        });
    </script>
