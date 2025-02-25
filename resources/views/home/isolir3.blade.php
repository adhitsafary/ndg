<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelanggan Isolir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #002fff, #d10000);
        }



        .card {
            background: linear-gradient(135deg, #666666, #111111);
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
        }

        p {
            background: linear-gradient(to right, #001aff, #f70000);
        }
    </style>
</head>

<body>
    <div class="mt-2 container text-center">
        <br>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3 justify-content-center">
            <?php foreach ($mergedResults as $data) : ?>
            <?php if ($data['tgl_tagih_plg'] >= 21 && $data['tgl_tagih_plg'] <= 30) : ?>
            <div class="col mb-2">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-center" style="color: white">
                            Tanggal Tagihan: <?= $data['tgl_tagih_plg'] ?>
                        </h5>
                        <p class="card-text" style="color: white"><strong>Pelanggan Isolir:</strong></p>
                        <ul>
                            <?php
                            $pelangganIsolir = \App\Models\Pelanggan::where('tgl_tagih_plg', $data['tgl_tagih_plg'])->where('status_pembayaran', 'isolir')->get();
                            ?>
                            <?php foreach ($pelangganIsolir as $pelanggan) : ?>
                            <li style="font-weight: 700; color:white; ">
                                <?= $pelanggan->nama_plg ?> - <?= $pelanggan->alamat_plg ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>



<script>
    setTimeout(() => {
        window.location.href = "https://Maju .net.com";
    }, 10000);
</script>




</html>
