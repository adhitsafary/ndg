<?php

namespace App\Http\Controllers;

use App\Models\X100c;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class X100Controller extends Controller
{
    public function ambilData()
    {
        $ip = '192.168.1.9';
        $key = 0;

        // Membuka koneksi ke mesin absensi
        $connect = fsockopen($ip, 80, $errno, $errstr, 1);
        if ($connect) {
            $soap_request = "<GetAttLog>
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

                // Validasi waktu dan format waktu yang valid
                if ($this->isValidDateTime($waktu)) {
                    $tanggal = date('Y-m-d', strtotime($waktu)); // Ambil tanggal dari waktu

                    // Cek apakah data sudah ada berdasarkan PIN dan tanggal
                    $existing = X100C::where('pin', $pin)
                        ->whereDate('waktu', $tanggal)
                        ->exists();

                    if (!$existing) {
                        // Tentukan kategori status berdasarkan kode status
                        $kategoriStatus = $this->tentukanKategoriStatus($status);

                        // Simpan data absensi baru ke database
                        try {
                            X100C::create([
                                'pin' => $pin,
                                'nama' => $nama,
                                'waktu' => $waktu,
                                'status' => $kategoriStatus, // Simpan kategori status
                            ]);
                            $data[] = [
                                'pin' => $pin,
                                'nama' => $nama,
                                'waktu' => $waktu,
                                'status' => $kategoriStatus,
                            ];
                            Log::info("Data berhasil disimpan: PIN = $pin, Waktu = $waktu, Status = $kategoriStatus");
                        } catch (\Exception $e) {
                            Log::error("Gagal menyimpan data: " . $e->getMessage());
                        }
                    } else {
                        Log::info("Data duplikat ditemukan: PIN = $pin, Tanggal = $tanggal");
                    }
                } else {
                    Log::info("Format waktu tidak valid: $waktu");
                }
            }

            return response()->json($data);
        } else {
            return response()->json(['error' => 'Koneksi ke mesin absensi gagal.'], 500);
        }
    }

    private function ambilNama($pin)
    {
        // Ambil nama pengguna berdasarkan PIN
        $ip = '192.168.1.9';
        $key = 0;

        $connect = fsockopen($ip, 80, $errno, $errstr, 1);
        if ($connect) {
            $soap_request = "<GetUserInfo>
                <ArgComKey xsi:type=\"xsd:integer\">$key</ArgComKey>
                <Arg><PIN xsi:type=\"xsd:integer\">$pin</PIN></Arg>
            </GetUserInfo>";

            $newLine = "\r\n";
            fputs($connect, "POST /iWsService HTTP/1.0" . $newLine);
            fputs($connect, "Content-Type: text/xml" . $newLine);
            fputs($connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
            fputs($connect, $soap_request . $newLine);

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

    private function tentukanKategoriStatus($status)
    {
        // Tentukan kategori status berdasarkan nilai status yang diterima
        switch ($status) {
            case '0': // Misalnya kode status 0 untuk "Masuk"
                return 'Masuk';
            case '1': // Kode status 1 untuk "Pulang"
                return 'Pulang';
            case '2': // Kode status 2 untuk "Masuk Lembur"
                return 'Masuk Lembur';
            case '3': // Kode status 3 untuk "Keluar Lembur"
                return 'Keluar Lembur';
            default:
                return 'Status Tidak Dikenal';
        }
    }

    public function index()
    {
        // Ambil data absensi dari database
        $data = X100C::orderBy('waktu', 'desc')->get();
        return view('x100c.index2', compact('data'));
    }
}
