<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spin Wheel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body {
            background-color: #282c34;
            color: white;
            text-align: center;
        }

        #wheel-container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }

        #wheel {
            width: 500px;
            height: 500px; 
            border-radius: 50%;
            border: 5px solid black;
            position: relative;
            overflow: hidden;
            background: #444;
        }

        .segment {
            width: 50%;
            height: 50%;
            position: absolute;
            transform-origin: bottom right;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .segment span {
            position: absolute;
            transform-origin: center;
            transform: rotate(calc(var(--angle) * -1deg)) translateY(-70px);
            font-weight: bold;
            color: white;
            font-size: 18px;
            text-align: center;
            width: 100px;
        }

        #arrow {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
        }

        #resultModal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            color: black;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            display: none;
            font-size: 24px;
            font-weight: bold;
            animation: popUp 0.5s ease-in-out;
        }

        @keyframes popUp {
            0% {
                transform: translate(-50%, -50%) scale(0.5);
                opacity: 0;
            }

            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <h2 class="mt-4">Spinner Net Digital Group</h2>
    <a href="{{ route('spin.create') }}" class="btn btn-primary">Tambah Barang</a>

    <div id="wheel-container">
        <img id="arrow" src="{{ asset('asset/img/icon/panah.png') }}" alt="Pointer">
        <div id="wheel">
            @foreach ($options as $key => $option)
                <div class="segment"
                    style="--angle: {{ $key * (360 / count($options)) }}; transform: rotate({{ $key * (360 / count($options)) }}deg); background: {{ sprintf('#%06X', mt_rand(0, 0xffffff)) }};">
                    <span>{{ $option->name }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <button onclick="spinWheel()" class="btn btn-success mt-3">Spin!</button>

    <div id="resultModal"></div>

    <audio id="winSound" src="/sounds/win.mp3"></audio>
    <audio id="spinSound" src="/sounds/spin.mp3"></audio>

    <script>
        function spinWheel() {
            document.getElementById('spinSound').play();
            let degrees = Math.floor(Math.random() * 3600) + 1800;
            gsap.to("#wheel", {
                rotation: degrees,
                duration: 5,
                ease: "power4.out",
                onComplete: function() {
                    let finalRotation = degrees % 360;
                    let segmentSize = 360 / {{ count($options) }};
                    let winningIndex = Math.round(finalRotation / segmentSize) % {{ count($options) }};
                    let selected = document.querySelectorAll('.segment span')[winningIndex].textContent;
                    document.getElementById('resultModal').innerHTML = "🎉 Selamat! Anda mendapatkan: " +
                        selected + " 🎉";
                    document.getElementById('resultModal').style.display = "block";
                    document.getElementById('winSound').play();
                    setTimeout(() => {
                        document.getElementById('resultModal').style.display = "none";
                    }, 4000);
                }
            });
        }
    </script>
</body>

</html>
