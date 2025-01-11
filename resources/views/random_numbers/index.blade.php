<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengacakan Angka</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .copy-btn {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
            border: none;
            background: none;
            padding: 0;
            font-size: 0.9em;
        }
    </style>
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Angka berhasil disalin ke clipboard!');
            }).catch(err => {
                alert('Gagal menyalin teks: ' + err);
            });
        }

        function showDetail(numbers) {
            const container = document.getElementById('detailContainer');
            container.innerHTML = '';

            numbers.forEach((num, index) => {
                if (index % 5 === 0) {
                    const row = document.createElement('div');
                    row.className = 'row mb-3';
                    container.appendChild(row);
                }

                const col = document.createElement('div');
                col.className = 'col-md-6';
                col.innerHTML = `
                    <div class="p-2 border text-center">
                        <strong>${index + 1}.</strong> ${num}
                        <button onclick="copyToClipboard('${num}')" class="btn btn-sm btn-link copy-btn">Salin</button>
                    </div>`;
                container.lastChild.appendChild(col);
            });

            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        }

        function confirmDelete(quantity) {
            return confirm(`Anda akan menghapus data dengan jumlah angka: ${quantity}. Lanjutkan?`);
        }
    </script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Pengacakan Angka</h1>
        <br><br>

        <!-- Form Input -->
        <form action="{{ route('random_numbers.generate') }}" method="POST" class="mb-4">
            @csrf
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="quantity" class="form-label">Jumlah Angka</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="range" class="form-label">Kode Rahasia</label>
                    <input type="text" name="range" id="range" class="form-control" required
                        placeholder="Contoh: 25131">
                </div>
                <div class="col-md-4">
                    <label for="suffix" class="form-label">Suffix </label>
                    <input type="text" name="suffix" id="suffix" class="form-control"
                        placeholder="Contoh: @net.net">
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Acak</button>
        </form>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabel Hasil -->
        <h2>Hasil Pengacakan</h2>
        <br>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jumlah Kode</th>
                    <th>Kode Rahasia</th>
                    <th>Suffix</th>
                    <th>Hasil</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $result)
                    @php
                        $decodedNumbers = is_string($result->numbers)
                            ? json_decode($result->numbers, true)
                            : $result->numbers;
                        $preview = is_array($decodedNumbers) ? array_slice($decodedNumbers, 0, 3) : [];
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $result->quantity }}</td>
                        <td>{{ $result->range }}</td>
                        <td>{{ $result->suffix }}</td>
                        <td>
                            {{ implode(', ', $preview) }}{{ count($decodedNumbers) > 5 ? '...' : '' }}
                        </td>
                        <td>{{ $result->created_at }}</td>
                        <td>
                            <!-- <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $result->id }}">Edit</button> -->
                            <button class="btn btn-info btn-sm"
                                onclick="showDetail({{ json_encode($decodedNumbers) }})">Detail</button>
                            <form action="{{ route('random_numbers.delete', $result->id) }}" method="POST"
                                style="display:inline;" onsubmit="return confirmDelete('{{ $result->quantity }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Kode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="detailContainer" class="row gy-2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    @foreach ($results as $result)
        <div class="modal fade" id="editModal{{ $result->id }}" tabindex="-1"
            aria-labelledby="editModalLabel{{ $result->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('random_numbers.update', $result->id) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel{{ $result->id }}">Edit Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="quantity{{ $result->id }}" class="form-label">Jumlah Angka</label>
                                <input type="number" name="quantity" id="quantity{{ $result->id }}"
                                    class="form-control" value="{{ $result->quantity }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="range{{ $result->id }}" class="form-label">Range</label>
                                <input type="text" name="range" id="range{{ $result->id }}"
                                    class="form-control" value="{{ $result->range }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="suffix{{ $result->id }}" class="form-label">Suffix</label>
                                <input type="text" name="suffix" id="suffix{{ $result->id }}"
                                    class="form-control" value="{{ $result->suffix }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
