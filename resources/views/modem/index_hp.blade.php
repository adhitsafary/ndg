@extends($layout)
@section('konten')
    <div class="card m-5">
        <div class="card m-5">
            <a href="/modem/create/" class="btn btn=primary">Tambah data</a>
        </div>
        <div class="row">
            @foreach ($modem as $modem)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $modem->model }}</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>SN Modem:</strong>
                                {{ preg_match('/SN:([A-Za-z0-9]+)/', $modem->sn_modem, $matches) ? $matches[1] : (preg_match('/&sn=([A-Za-z0-9]+)/', $modem->sn_modem, $matches) ? $matches[1] : 'Tidak ditemukan') }}
                            </p>
                            <p><strong>Tanggal Keluar:</strong> {{ $modem->tgl_keluar }}</p>
                            <p><strong>User:</strong> {{ $modem->user }}</p>
                            <p><strong>ID MikroTik:</strong> {{ $modem->id_mikrotik }}</p>
                            <p><strong>Keterangan:</strong> {{ $modem->keterangan }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('modem.edit', $modem->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('modem.destroy', $modem->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
