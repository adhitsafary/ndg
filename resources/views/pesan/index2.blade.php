@extends($layout)

@section('konten')
    <div class="card m-5">
        <h3 style="font-weight: 500">Pesan</h3>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Penerima</th>
                    <th>Pesan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </tbody>
            @forelse ($pesan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->admin }}</td>
                    <td>{{ $item->penerima }}</td>
                    <td>{{ $item->pesan }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>
                        <a href="{{ route('pesan.create') }}">Pesan</a>
                    </td>
                </tr>

            @empty
            @endforelse

        </table>
    </div>
@endsection
