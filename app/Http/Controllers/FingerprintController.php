<?php

namespace App\Http\Controllers;

use App\Services\X100CService;
use Illuminate\Http\Request;

class FingerprintController extends Controller
{
    protected $x100cService;

    public function __construct(X100CService $x100cService)
    {
        $this->x100cService = $x100cService;
    }

    public function getAttendance()
    {
        // Ambil data absensi dari mesin X100-C
        $attendanceData = $this->x100cService->getAttendanceData();

        // Tampilkan data atau simpan ke database sesuai kebutuhan
        return response()->json($attendanceData);
    }
}
