@extends($layout)

@section('konten')
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
        </div>


        <div class="row mb-3">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card-biru_tua h-70">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="row text text-white font-weight-bold  mb-1">
                                    <div class="ml-2 mr-3">
                                        <img class="" height="50px" src="{{ asset('asset/img/icon/user.png') }}">
                                    </div>
                                    <div>
                                        <div>
                                            Pemasangan Bulan Ini
                                        </div>
                                        <div class=" h6 mb-0 mr-0 font-weight-bold text-white font-bold">Pelanggan :
                                            {{ $total_pemasangan }} Orang
                                        </div>
                                    </div>

                                </div>

                                <div class="mb-0 text-muted">
                                    <span class="text text-white font-weight-bold "></span>
                                    <span class="text text-white font-weight-bold "></span>

                                    <span class="text text-white font-weight-bold "></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <!-- New User Card Example -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card-biru_tua h-70">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="row text text-white font-weight-bold  mb-1">
                                    <div class="ml-2 mr-3">
                                        <img class="" height="50px" src="{{ asset('asset/img/perbaikan.png') }}">
                                    </div>
                                    <div>
                                        <div>
                                            Perbaikan Bulan Ini
                                        </div>
                                        <div class=" h6 mb-0 mr-0 font-weight-bold text-white font-bold">Pelanggan :
                                            {{ $total_perbaikan_b }} Orang
                                        </div>
                                    </div>

                                </div>
                                <!-- Menampilkan pendapatan dengan format rupiah -->
                                <div class="mb-0 text-muted">
                                    <span class="text text-white font-weight-bold "></span>
                                    <span class="text text-white mr-2 font-weight-bold "><i class="text text-white"></i>
                                    </span>
                                    <span class="text text-white  font-weight-bold ">
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <!-- New User Card Example -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card-biru_tua h-70">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="row text text-white font-weight-bold  mb-1">
                                    <div class="ml-2 mr-3">
                                        <img class="" height="50px" src="{{ asset('asset/img/icon/modem.png') }}">
                                    </div>
                                    <div>
                                        <div>
                                            Total Modem yang ada
                                        </div>
                                        <div class=" h6 mb-0 mr-0 font-weight-bold text-white font-bold">Modem :
                                            {{ $total_modem }} Pcs
                                        </div>
                                    </div>

                                </div>
                                <!-- Menampilkan pendapatan dengan format rupiah -->
                                <div class="mb-0 text-muted">
                                    <span class="text text-white font-weight-bold "></span>
                                    <span class="text text-white mr-2 font-weight-bold "><i class="text text-white"></i>
                                    </span>
                                    <span class="text text-white  font-weight-bold ">
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card-biru_tua h-70">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="row text text-white font-weight-bold  mb-1">
                                    <div class="ml-2 mr-3">
                                        <img class="" height="50px" src="{{ asset('asset/img/icon/absen.png') }}">
                                    </div>
                                    <div>
                                        <div>
                                            Data kehadiran Hari ini
                                        </div>
                                        <div class=" h6 mb-0 mr-0 font-weight-bold text-white font-bold">Jumlah :
                                            {{ $total_kehadiran }} Orang
                                        </div>
                                    </div>

                                </div>
                                <!-- Menampilkan pendapatan dengan format rupiah -->
                                <div class="mb-0 text-muted">
                                    <span class="text text-white font-weight-bold "></span>
                                    <span class="text text-white mr-2 font-weight-bold "><i class="text text-white"></i>
                                    </span>
                                    <span class="text text-white  font-weight-bold ">
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>









            <!-- Chart Bar dan Line -->
            <div class="col-xl-70 col-lg-8 mb-3">
                <!-- Memperbesar tampilan card-body -->
                <div class="card-biru_tua" style="font-size: 1.5rem; height: 500px;">
                    <!-- Menambah ukuran font dan tinggi card -->
                    <div class="card-body" style="height: 100%;"> <!-- Memastikan card-body mengikuti tinggi card -->
                        <h6 class="text text-white font-weight-bold">Pemasangan Bulan Ini</h6>
                        <!-- Membesarkan judul -->
                        <div class="chart-area" style="height: 400px;"> <!-- Menyesuaikan tinggi area chart -->
                            <canvas id="pendapatanChart" width="850" height="300"></canvas>
                            <!-- Untuk Bar/Line Chart -->
                        </div>
                    </div>
                </div>
            </div>



            <!-- Pie Chart -->
            <div class="card-biru_tua col-xl-4 col-lg- mt-0 h-100 mb-3 ">
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






        </div>






        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // Data dari backend (controller Laravel) untuk chart batang/garis
            const labels = @json($labels); // Label untuk tanggal (1-30)
            const totalUsers = @json($totalUsers); // Jumlah user per tanggal
            const totalPembayaran = @json($totalPembayaran); // Total pembayaran per tanggal

            // Inisialisasi Bar dan Line Chart
            const ctx1 = document.getElementById('pendapatanChart').getContext('2d');
            const pendapatanChart = new Chart(ctx1, {
                type: 'bar', // Tipe chart batang (bar)
                data: {
                    labels: labels, // Label (Tanggal 1-30)
                    datasets: [{
                            label: 'Jumlah Pelanggan Baru',
                            data: totalUsers, // Data jumlah pengguna per hari
                            backgroundColor: 'rgba(255, 255, 255, 0.2)', // Warna batang putih semi transparan
                            borderColor: 'rgb(255, 157, 0)', // Warna border batang putih
                            borderWidth: 1,
                            pointRadius: 8, // Mengatur ukuran titik (besar)
                            pointHoverRadius: 10 // Mengatur ukuran titik saat di-hover (lebih besar)
                        },
                        {
                            label: 'Total Pembayaran Aktivasi (Rp)',
                            data: totalPembayaran, // Data total pembayaran per hari
                            backgroundColor: 'rgba(255, 255, 255, 0.2)', // Warna grafik garis putih semi transparan
                            borderColor: 'rgb(255, 157, 0)', // Warna border garis putih
                            borderWidth: 1,
                            type: 'line', // Grafik tipe garis (line)
                            pointRadius: 8, // Mengatur ukuran titik untuk garis (besar)
                            pointHoverRadius: 10 // Mengatur ukuran titik saat di-hover (lebih besar)
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

    <!-- Modal Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelLogout"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabelLogout">Tidak !</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to logout?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                    <a href="/logout" class="btn btn-primary">Logout</a>
                </div>
            </div>
        </div>
    </div>



    <!-- Footer -->
    <br><br><br><br><br><br><br>
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>copyright &copy;
                    <script>
                        document.write(new Date().getFullYear());
                    </script> - developed by
                    <b><a href="" target="_blank">NetNet Digital Group</a></b>
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

<!-- HTML untuk Running Text dengan Data -->
<div class="running-text-container">
    <div class="running-text">
        <span>
            Sisa Tagihan Hari Ini: Rp {{ number_format($totalTagihanTertagih, 0, ',', '.') }} User:
            {{ $totalUserTertagih }} ||
            Tagihan Hari Ini: Rp {{ number_format($totalTagihanHariIni, 0, ',', '.') }} User:
            {{ $jumlahPelangganMembayarHariIni }} ||
            Tertagih: Rp {{ number_format($totalPendapatanharian_semua, 0, ',', '.') }} User:
            {{ $totalUserHarian_semua }}
        </span>
    </div>
</div>


<script>
    // Auto-refresh halaman setiap 30 detik
    setInterval(() => {
        location.reload(); // Reload halaman
    }, 30000); // 30.000 ms = 30 detik
</script>
