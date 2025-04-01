@extends($layout)

@section('konten')
    <div class="card m-5">
        <a href="{{ route('pesan.create') }}" class="btn btn-warning btn-sm container" >Create</a>
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
                        <a href="{{ route('pesan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('pesan.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada pesan</td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
