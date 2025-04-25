@extends($layout) {{-- ganti sesuai layoutmu --}}

@section('konten')
    <div class="">

        <div class="card m-3">
            <h4 class="mb-3">Activity Log Saya</h4>
            <table class="  table table-bordered">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Aktivitas</th>
                        <th>Modul</th>
                        <th>Detail</th>
                        <th>IP Address</th>
                        <th>Perangkat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d-m-Y H:i') }}</td>
                            <td>{{ $log->activity }}</td>
                            <td>{{ $log->module }}</td>
                            <td>{{ $log->details }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ $log->user_agent }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada aktivitas tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-center ml-3">
            {{ $logs->links() }}
        </div>
    </div>
@endsection
