@extends($layout)

@section('konten')
    <div class="m-4">
        <div class="card-biru_tua" style="font-size: 1.5rem; ">
            <div class="card-body d-flex flex-column" style="height: 100%;">
                <h6 class="text-white font-weight-bold mb-3">Aktifitas Admin</h6>

                {{-- Tabel ditaruh di atas --}}

                <div class="table-responsive" style="max-height: 800px; overflow-y: auto;">
                    <table class="table table-sm table-bordered text-dark bg-white text-sm mb-0" style="font-size: 11px;">

                        <thead class="custom-cell warning">
                            <tr style="background: sandybrown">
                                <th class="p-1" style="width: 8%;">Tanggal</th>
                                <th class="p-1" style="width: 8%;">Nama Admin</th>
                                <th class="p-1" style="width: 8%;">Aktivitas</th>
                                <th class="p-1" style="width: 8%;">Kategori</th>
                                <th class="p-1" style="width: 8%;">Detail</th>
                                <th class="p-1" style="width: 8%;">Alamat IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                @php
                                    $details = json_decode($log->details);
                                @endphp
                                <tr>
                                    <td class="text-center p-1">{{ $log->created_at->format('d-m-Y H:i') }}</td>
                                    <td class="text-center p-1">{{ $log->user->name ?? 'Guest' }}</td>
                                    <td class="text-center p-1">{{ $log->activity }}</td>
                                    <td class="text-center p-1">{{ $log->module }}</td>
                                    <td class="text-center p-1">
                                        @php
                                            $details = json_decode($log->details, true); // decode as array biar bisa ambil urutan
                                            $firstTwo = array_slice($details, 1, 2); // ambil dua data pertama
                                        @endphp

                                        @foreach ($firstTwo as $key => $value)
                                            {{ ucfirst($key) }}:
                                            {{ is_numeric($value) ? $value : $value }}<br>
                                        @endforeach

                                    </td>

                                    <td class="text-center p-1">{{ $log->ip_address }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Jika ingin ada konten lain di bawah tabel, bisa ditambahkan di sini --}}
            </div>
        </div>
    </div>
@endsection
