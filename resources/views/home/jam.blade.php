<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jam Digital Ramadhan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #0b3d2e;
            color: white;
            font-family: Arial, sans-serif;
            flex-direction: column;
            text-align: center;
        }

        .frame {
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('frame-ramadhan.png') no-repeat center center;
            background-size: cover;
            z-index: -1;
        }

        .clock {
            font-size: 120px;
            font-weight: bold;
            margin-top: 20px;
        }

        .date {
            font-size: 32px;
            margin-top: 10px;
        }

        .lantern {
            position: absolute;
            top: 10px;
            width: 100px;
            animation: swing 2s infinite alternate;
        }



        @keyframes swing {
            from {
                transform: rotate(-5deg);
            }

            to {
                transform: rotate(5deg);
            }
        }

        .schedule {
            margin-top: 20px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid white;
            text-align: center;
        }

        .title_kecil {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .title_wrapper {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 26px;
            font-weight: bold;
            color: white;
        }

        .title_besar {
            font-size: 40px;
            font-weight: bold;
            color: #FFD700;
            display: block;
            margin-top: 10px;
        }

        h3 {
            margin-bottom: 10px;
            color: #FFD700;
        }

        .schedule {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .container {
            max-width: 90%;
            /* Bisa diubah jadi 100% kalau mau lebih lebar */
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            padding: 15px;
            border: 1px solid white;
            text-align: center;
            font-size: 18px;
            /* Supaya teks lebih besar */
        }

        th {
            background-color: rgba(255, 255, 255, 0.2);
        }


        .lantern-container {
            position: absolute;
            top: 0%;
            /* Tetap di atas halaman */
            width: 100%;
        }

        .lantern {
            width: 90px;
            /* Ukuran gambar */
            animation: swing 2s infinite alternate;
            position: absolute;
        }



        .lantern-center {
            left: 50%;
            /* Tepat di tengah */
            transform: translateX(-50%);
            /* Agar benar-benar center */
        }

        .lantern-center1 {
            left: 45%;
            /* Tepat di tengah */
            transform: translateX(-50%);
            /* Agar benar-benar center */
        }

        .lantern-center2 {
            left: 55%;
            /* Tepat di tengah */
            transform: translateX(-50%);
            /* Agar benar-benar center */
        }

        .lantern-center3 {
            left: 60%;
            /* Tepat di tengah */
            transform: translateX(-50%);
            /* Agar benar-benar center */
        }

        .lantern-center4 {
            left: 65%;
            /* Tepat di tengah */
            transform: translateX(-50%);
            /* Agar benar-benar center */
        }

        .lantern-center5 {
            left: 40%;
            /* Tepat di tengah */
            transform: translateX(-50%);
            /* Agar benar-benar center */
        }

        .lantern-center6 {
            left: 35%;
            /* Tepat di tengah */
            transform: translateX(-50%);
            /* Agar benar-benar center */
        }

        .lantern-center7 {
            left: 30%;
            /* Tepat di tengah */
            transform: translateX(-50%);
            /* Agar benar-benar center */
        }




        .lantern-right-mid {
            right: 22%;
            /* Di antara kanan & tengah */
        }

        .lantern-right {
            right: 5%;
            /* Paling kanan */
        }

        .lantern-right1 {
            right: 10%;
            /* Paling kanan */
        }

        .lantern-right2 {
            right: 15%;
            /* Paling kanan */
        }

        .lantern-right3 {
            right: 20%;
            /* Paling kanan */
        }


        .lantern-left {
            left: 5%;
            /* Paling kiri */
        }

        .lantern-left1 {
            left: 10%;
            /* Paling kiri */
        }

        .lantern-left2 {
            left: 15%;
            /* Paling kiri */
        }

        .lantern-left3 {
            left: 20%;
            /* Paling kiri */
        }


        .lantern-left-mid {
            left: 22%;
            /* Di antara kiri & tengah */
        }


        /* Animasi Swing */
        @keyframes swing {
            from {
                transform: rotate(-5deg);
            }

            to {
                transform: rotate(5deg);
            }
        }
    </style>
</head>

<body>

    <div class="title_wrapper">
        <div class="title">Di Bulan Suci Ramadhan ini, <span class="title_besar">Net Digital Group</span> mengucapkan,
        </div>
        <div class="title_besar">Selamat menunaikan Ibadah Puasa 1446 Hijriah</div>
        <div class="title">Semoga sehat dan dilancarkan ibadahnya</div>
        <div class="title">
            <span id="countdown" style="font-size: 2em; font-weight: bold; display: inline-block;"
                class="title_besar"></span>
            Menuju Buka Puasa
        </div>

    </div>

    <div class="lantern-container">
        <img src="{{ asset('asset/img/icon/lentera.png') }}" class="lantern lantern-left">
        <img src="{{ asset('asset/img/icon/ramadhan.png') }}" class="lantern lantern-left1">
        <img src="{{ asset('asset/img/icon/ramadhan.png') }}" class="lantern lantern-left2">
        <img src="{{ asset('asset/img/icon/lentera2.png') }}" class="lantern lantern-left3">

        <img src="{{ asset('asset/img/icon/lentera2.png') }}" class="lantern lantern-center">
        <img src="{{ asset('asset/img/icon/lentera2.png') }}" class="lantern lantern-center1">
        <img src="{{ asset('asset/img/icon/lentera.png') }}" class="lantern lantern-center2">
        <img src="{{ asset('asset/img/icon/lentera.png') }}" class="lantern lantern-center3">
        <img src="{{ asset('asset/img/icon/lentera.png') }}" class="lantern lantern-center4">
        <img src="{{ asset('asset/img/icon/lentera.png') }}" class="lantern lantern-center5">
        <img src="{{ asset('asset/img/icon/lentera.png') }}" class="lantern lantern-center6">
        <img src="{{ asset('asset/img/icon/lentera.png') }}" class="lantern lantern-center7">


        <img src="{{ asset('asset/img/icon/lentera.png') }}" class="lantern lantern-right">
        <img src="{{ asset('asset/img/icon/ramadhan.png') }}" class="lantern lantern-right1">
        <img src="{{ asset('asset/img/icon/ramadhan.png') }}" class="lantern lantern-right2">
        <img src="{{ asset('asset/img/icon/lentera2.png') }}" class="lantern lantern-right3">
    </div>



    <div id="clock" class="clock">00:00:00</div>
    <div id="date" class="date">Hari, 01-01-2025</div>

    <div class="schedule container">
        <div class="container">
            <h3>Jadwal Imsakiyah</h3>
            <table class="table">
                <tr>
                    <th>Waktu</th>
                    <td>Imsak</td>
                    <td>Subuh</td>
                    <td>Dzuhur</td>
                    <td>Ashar</td>
                    <td>Maghrib</td>
                    <td>Isya</td>
                </tr>
                <tr>
                    <th>Jam</th>
                    <td id="imsak">-</td>
                    <td id="subuh">-</td>
                    <td id="dzuhur">-</td>
                    <td id="ashar">-</td>
                    <td id="maghrib">-</td>
                    <td id="isya">-</td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        function updateClock() {
            let now = new Date();
            let hours = now.getHours().toString().padStart(2, '0');
            let minutes = now.getMinutes().toString().padStart(2, '0');
            let seconds = now.getSeconds().toString().padStart(2, '0');
            let days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            let dayName = days[now.getDay()];
            let day = now.getDate().toString().padStart(2, '0');
            let month = (now.getMonth() + 1).toString().padStart(2, '0');
            let year = now.getFullYear();

            document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
            document.getElementById('date').textContent = `${dayName}, ${day}-${month}-${year}`;
        }

        setInterval(updateClock, 1000);
        updateClock();

        async function fetchPrayerTimes() {
            let cityId = 1216; // ID Kota (Jakarta). Ganti sesuai lokasi.
            let today = new Date();
            let formattedDate =
                `${today.getFullYear()}-${(today.getMonth() + 1).toString().padStart(2, '0')}-${today.getDate().toString().padStart(2, '0')}`;
            let apiURL = `https://api.myquran.com/v2/sholat/jadwal/${cityId}/${formattedDate}`;

            try {
                let response = await fetch(apiURL);
                let data = await response.json();
                console.log(data); // Debugging di console

                if (data.status && data.data.jadwal) {
                    let jadwal = data.data.jadwal;
                    document.getElementById('imsak').textContent = jadwal.imsak + " WIB";
                    document.getElementById('subuh').textContent = jadwal.subuh + " WIB";
                    document.getElementById('dzuhur').textContent = jadwal.dzuhur + " WIB";
                    document.getElementById('ashar').textContent = jadwal.ashar + " WIB";
                    document.getElementById('maghrib').textContent = jadwal.maghrib + " WIB";
                    document.getElementById('isya').textContent = jadwal.isya + " WIB";

                    startCountdown(jadwal.maghrib);
                } else {
                    console.error("Format API tidak sesuai:", data);
                }
            } catch (error) {
                console.error("Gagal mengambil data jadwal sholat", error);
            }
        }

        function startCountdown(maghribTime) {
            if (!maghribTime) {
                console.error("Waktu Maghrib tidak tersedia.");
                return;
            }

            let now = new Date();
            let [hour, minute] = maghribTime.split(":").map(Number);
            let maghribDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hour, minute, 0);

            function updateCountdown() {
                let currentTime = new Date();
                let timeDiff = maghribDate - currentTime;

                if (timeDiff <= 0) {
                    document.getElementById("countdown").textContent = "Waktu Maghrib Telah Tiba!";
                    clearInterval(countdownInterval);
                    return;
                }

                let hours = Math.floor(timeDiff / (1000 * 60 * 60));
                let minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

                document.getElementById("countdown").textContent = `${hours}j ${minutes}m ${seconds}d`;
            }

            updateCountdown();
            let countdownInterval = setInterval(updateCountdown, 1000);
        }

        document.addEventListener("DOMContentLoaded", fetchPrayerTimes);
    </script>


    <script>
        setTimeout(() => {
            window.location.href = "{{ url('https://tiara.netdigitalgroup.com/home/pemasangan') }}";
        }, 60000);
    </script>
</body>

</html>
