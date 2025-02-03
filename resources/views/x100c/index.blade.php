<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Data Absensi</h1>
        <button id="fetch-data" class="btn btn-primary mb-3">Ambil Data Absensi</button>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Waktu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="absensi-data">
                <tr>
                    <td colspan="4" class="text-center">Klik tombol "Ambil Data Absensi" untuk memuat data.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#fetch-data').on('click', function() {
            $('#absensi-data').html('<tr><td colspan="4" class="text-center">Memuat data...</td></tr>');

            $.ajax({
                url: "{{ route('x100c.index) }}",
                method: 'GET',
                success: function(response) {
                    let rows = '';
                    if (response.length > 0) {
                        response.forEach((item, index) => {
                            rows += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.pin}</td>
                                    <td>${item.waktu}</td>
                                    <td>${item.status}</td>
                                </tr>
                            `;
                        });
                    } else {
                        rows = '<tr><td colspan="4" class="text-center">Tidak ada data absensi.</td></tr>';
                    }
                    $('#absensi-data').html(rows);
                },
                error: function() {
                    $('#absensi-data').html('<tr><td colspan="4" class="text-center text-danger">Gagal mengambil data absensi.</td></tr>');
                }
            });
        });
    </script>
</body>
</html>
