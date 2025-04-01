
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <title>Jadwal Sholat</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            text-align: center;
            background-color: #0b3d2e;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.2);
            max-width: 90%;
            width: 400px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

        h3 {
            margin-bottom: 10px;
        }
    </style>
</head>

<body><br><br><br>
    <div class="card p-3 text-center">
        <h3>Jadwal Sholat Bulan Ini</h3>
        <table id="prayer-table">
            <tr>
                <th>Tanggal</th>
                <th>Imsak</th>
                <th>Subuh</th>
                <th>Dzuhur</th>
                <th>Ashar</th>
                <th>Maghrib</th>
                <th>Isya</th>
            </tr>
        </table>
    </div>

    <script>
        async function fetchMonthlyPrayerTimes() {
            let cityId = 1216;
            let today = new Date();
            let month = (today.getMonth() + 1).toString().padStart(2, '0');
            let year = today.getFullYear();
            let apiURL = `https://api.myquran.com/v2/sholat/jadwal/${cityId}/${year}/${month}`;

            try {
                let response = await fetch(apiURL);
                let data = await response.json();

                if (data.status && data.data.jadwal) {
                    let table = document.getElementById("prayer-table");
                    data.data.jadwal.forEach(jadwal => {
                        let row = table.insertRow(-1);
                        row.insertCell(0).textContent = jadwal.tanggal;
                        row.insertCell(1).textContent = jadwal.imsak;
                        row.insertCell(2).textContent = jadwal.subuh;
                        row.insertCell(3).textContent = jadwal.dzuhur;
                        row.insertCell(4).textContent = jadwal.ashar;
                        row.insertCell(5).textContent = jadwal.maghrib;
                        row.insertCell(6).textContent = jadwal.isya;
                    });
                } else {
                    console.error("Format API tidak sesuai", data);
                }
            } catch (error) {
                console.error("Gagal mengambil data jadwal sholat", error);
            }
        }

        document.addEventListener("DOMContentLoaded", fetchMonthlyPrayerTimes);
    </script>
</body>

</html>
