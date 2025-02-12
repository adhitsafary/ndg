<?php

namespace App\Http\Controllers;

use App\Models\X100c;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class X100Controller extends Controller
{


    public function ambilData()
    {
        $ip = '103.171.182.12';
        $port = 4370;
        $key = 0;

        // Membuka koneksi ke mesin absensi
        $connect = fsockopen($ip, $port, $errno, $errstr, 3);
        if (!$connect) {
            return response()->json(['error' => 'Koneksi ke mesin absensi gagal.'], 500);
        }

        // SOAP Request untuk mengambil log absensi
        $soap_request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <GetAttLog>
                <ArgComKey xsi:type=\"xsd:integer\">$key</ArgComKey>
                <Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg>
            </GetAttLog>";

        $newLine = "\r\n";
        fputs($connect, "POST /iWsService HTTP/1.0" . $newLine);
        fputs($connect, "Content-Type: text/xml" . $newLine);
        fputs($connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
        fputs($connect, $soap_request . $newLine);

        $buffer = "";
        while ($response = fgets($connect, 1024)) {
            $buffer .= $response;
        }
        fclose($connect);

        // Parsing data dari mesin absensi
        $buffer = $this->parseData($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
        $rows = explode("\r\n", $buffer);

        $data = [];
        foreach ($rows as $row) {
            $parsedRow = $this->parseData($row, "<Row>", "</Row>");
            $pin = $this->parseData($parsedRow, "<PIN>", "</PIN>");
            $waktu = $this->parseData($parsedRow, "<DateTime>", "</DateTime>");
            $status = $this->parseData($parsedRow, "<Status>", "</Status>");

            // Ambil nama pengguna berdasarkan PIN
            $nama = $this->ambilNama($pin);

            // Validasi waktu dan pastikan format benar
            if ($this->isValidDateTime($waktu)) {
                $tanggal = date('Y-m-d', strtotime($waktu));
                $jam = date('H:i:s', strtotime($waktu));

                // Tentukan kategori status absensi
                $kategoriStatus = $this->tentukanKategoriStatus($status);

                // Cek apakah data sudah ada untuk menghindari duplikasi
                $existing = X100C::where('pin', $pin)
                    ->whereDate('waktu', $tanggal)
                    ->where('status', $kategoriStatus)
                    ->exists();

                if (!$existing) {
                    try {
                        // Simpan data ke database
                        X100C::create([
                            'pin' => $pin,
                            'nama' => $nama,
                            'waktu' => $waktu,
                            'status' => $kategoriStatus,
                        ]);

                        // Tambahkan data ke array response
                        $data[] = [
                            'pin' => $pin,
                            'nama' => $nama,
                            'waktu' => $waktu,
                            'status' => $kategoriStatus,
                        ];

                        Log::info("Data berhasil disimpan: PIN = $pin, Waktu = $waktu, Status = $kategoriStatus");

                        // Kirim notifikasi Telegram
                        $this->sendTelegramNotification("Nama: $nama, Waktu: $jam, Status: $kategoriStatus");
                    } catch (\Exception $e) {
                        Log::error("Gagal menyimpan data: " . $e->getMessage());
                    }
                } else {
                    Log::info("Data duplikat: PIN = $pin, Tanggal = $tanggal, Status = $kategoriStatus");
                }
            } else {
                Log::warning("Format waktu tidak valid: $waktu");
            }
        }

        return response()->json($data);
    }
    public function sendTelegramNotification($message)
    {
        $telegramApiUrl = "https://api.telegram.org/bot7698682599:AAGD6gD8XtrDbCJ8Qd-D3FlbGgsSu08ArhU/sendMessage";
        $chatId = "-4768802677";

        $url = $telegramApiUrl . "?chat_id=" . $chatId . "&text=" . urlencode($message);

        // Menggunakan file_get_contents untuk mengirim pesan
        file_get_contents($url);
    }


    public function sendTelegramNotification2($message)
    {
        $telegramApiUrl = "7698682599:AAGD6gD8XtrDbCJ8Qd-D3FlbGgsSu08ArhU";
        $chatId = "-4768802677";

        $url = $telegramApiUrl . "?chat_id=" . $chatId . "&text=" . urlencode($message);

        // Menggunakan file_get_contents untuk mengirim pesan
        file_get_contents($url);
    }








    private function ambilNama($pin)
    {
        $ip = '103.171.182.12:4370';
        $key = 0;

        $connect = fsockopen($ip, 4370, $errno, $errstr, 1);
        if ($connect) {
            $soap_request = "<GetUserInfo>
                <ArgComKey xsi:type=\"xsd:integer\">$key</ArgComKey>
                <Arg><PIN xsi:type=\"xsd:integer\">$pin</PIN></Arg>
            </GetUserInfo>";

            $newLine = "\r\n";
            fputs($connect, "POST /iWsService HTTP/1.0" . $newLine);
            fputs($connect, "Content-Type: text/xml" . $newLine);
            fputs($connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
            fputs($connect, $soap_request . $newLine); // Perbaikan di sini

            $buffer = "";
            while ($response = fgets($connect, 1024)) {
                $buffer .= $response;
            }

            $nama = $this->parseData($buffer, "<Name>", "</Name>");
            return $nama ?: 'Tidak Diketahui';
        }

        return 'Tidak Diketahui';
    }


    private function parseData($data, $startTag, $endTag)
    {
        // Fungsi untuk mengambil data antara tag XML
        $data = " " . $data;
        $result = "";
        $start = strpos($data, $startTag);
        if ($start !== false) {
            $end = strpos(substr($data, $start), $endTag);
            if ($end !== false) {
                $result = substr($data, $start + strlen($startTag), $end - strlen($startTag));
            }
        }
        return $result;
    }

    private function isValidDateTime($dateTime)
    {
        // Validasi apakah waktu memiliki format yang benar
        return (bool)strtotime($dateTime);
    }

    //mungkin bukan 0,1,2,3, untuk nomer kategori status ya, kamu tau dari mana bahwa itu nomer 0,1,2,3 ?

    private function tentukanKategoriStatus($status)
    {
        switch ((string)$status) { // Konversi ke string untuk memastikan perbandingan cocok
            case '0':
                return 'Masuk';
            case '1':
                return 'Pulang';
            case '4':
                return 'Masuk Lembur';
            case '5':
                return 'Keluar Lembur';
            default:
                return 'Status Tidak Dikenal';
        }
    }


    public function index()
    {
        // Ambil data terbaru dari mesin absensi
        $this->ambilData();

        // Ambil semua data absensi setelah sinkronisasi
        $data = X100C::orderBy('waktu', 'desc')->get();

        // Ambil daftar nama unik
        $allNames = X100C::select('nama')->distinct()->pluck('nama');

        // Kirim data ke view
        return view('x100c.index2', compact('data', 'allNames'));
    }


    public function show(Request $request)
    {
        // Ambil nama dari request
        $nama = $request->input('nama');

        // Ambil data absensi berdasarkan nama (jika nama diberikan), atau semua data jika tidak ada nama
        $data = $nama
            ? X100C::where('nama', $nama)->orderBy('waktu', 'desc')->get()
            : X100C::orderBy('waktu', 'desc')->get();

        // Ambil daftar nama unik
        $allNames = X100C::select('nama')->distinct()->pluck('nama');

        // Kirim data ke view
        return view('x100c.index2', compact('data', 'allNames'));
    }

    public function show2(Request $request)
    {
        $nama = $request->input('nama');

        $this->ambilData();

        // Ambil data absensi berdasarkan nama (atau pin)
        $data = DB::table('x100c')
            ->when($nama, function ($query, $nama) {
                return $query->where('nama', $nama);  // Filter berdasarkan nama
            })
            ->get();

        // Mengelompokkan data berdasarkan nama atau pin
        $groupedData = $data->groupBy(function ($item) {
            return $item->nama . '-' . $item->pin; // Gabungkan nama dan pin sebagai key untuk pengelompokan
        });

        // Kirim data yang sudah dikelompokkan ke view
        return view('x100c.show', [
            'groupedData' => $groupedData,
            'allNames' => DB::table('x100c')->distinct()->pluck('nama')
        ]);
    }

    public function detail($nama, $pin)
    {
        // Ambil data absensi berdasarkan nama dan pin
        $data = DB::table('x100c')
            ->where('nama', $nama)
            ->where('pin', $pin)
            ->orderBy('waktu', 'asc')  // Urutkan berdasarkan waktu
            ->get();

        // Hitung jumlah terlambat, yaitu absensi masuk setelah jam 08:00:00
        $terlambatCount = $data->filter(function ($row) {
            return $row->status == 'Masuk' && Carbon::parse($row->waktu)->format('H:i:s') > '08:15:00';
        })->count();

        return view('x100c.detail', [
            'data' => $data,
            'nama' => $nama,
            'pin' => $pin,
            'terlambatCount' => $terlambatCount
        ]);
    }

    public function calculateAbsensiPercentage($nama, $pin)
    {
        // Ambil data absensi untuk 30 hari terakhir
        $startDate = Carbon::now()->subDays(30); // 30 hari terakhir
        $endDate = Carbon::now(); // Hari ini

        // Ambil data absensi berdasarkan nama dan pin dalam 30 hari terakhir
        $data = DB::table('x100c')
            ->where('nama', $nama)
            ->where('pin', $pin)
            ->whereBetween('waktu', [$startDate, $endDate])
            ->get();

        // Hitung total absensi Masuk (status "Masuk")
        $totalMasuk = $data->where('status', 'Masuk')->count();

        // Tentukan total hari (dianggap 30 hari terakhir)
        $totalHari = 30;

        // Hitung persentase absensi
        $persentaseAbsensi = round(($totalMasuk / $totalHari) * 100); // Bulatkan persentase

        // Kembalikan hasil ke view
        return view('x100c.show', [
            'nama' => $nama,
            'pin' => $pin,
            'persentaseAbsensi' => $persentaseAbsensi
        ]);
    }

    public function slipGaji($nama, $pin)
    {
        // Ambil data absensi berdasarkan nama dan pin
        $data = DB::table('x100c')
            ->where('nama', $nama)
            ->where('pin', $pin)
            ->orderBy('waktu', 'asc')
            ->get();

        // Gaji pokok dan rate lembur
        $gajiPokokPerHari = 100000;  // Gaji pokok per hari
        $rateLemburPerJam = 20000;  // Rate lembur per jam
        $potonganTerlambat = 5000;   // Potongan per keterlambatan

        // Hitung jumlah hari masuk, lembur, terlambat
        $jumlahMasuk = $data->where('status', 'Masuk')->count();
        $jumlahLembur = $data->where('status', 'Masuk Lembur')->count();
        $terlambatCount = $data->filter(function ($row) {
            return $row->status == 'Masuk' && Carbon::parse($row->waktu)->format('H:i:s') > '08:15:00';
        })->count();

        // Hitung total gaji
        $gajiMasuk = $jumlahMasuk * $gajiPokokPerHari;
        $gajiLembur = $jumlahLembur * $rateLemburPerJam;
        $potongan = $terlambatCount * $potonganTerlambat;
        $totalGaji = $gajiMasuk + $gajiLembur - $potongan;

        // Kirim data ke view
        return view('x100c.slipGaji', [
            'nama' => $nama,
            'pin' => $pin,
            'gajiMasuk' => $gajiMasuk,
            'gajiLembur' => $gajiLembur,
            'potongan' => $potongan,
            'totalGaji' => $totalGaji,
            'terlambatCount' => $terlambatCount,
        ]);
    }


    public function destroy($id)
    {
        $absensi = X100c::find($id);
        if (!$absensi) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $absensi->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
