<?php

namespace App\Services;

use SoapClient;

class X100CService
{
    protected $client;

    public function __construct()
    {
        // Ganti URL ini dengan alamat SOAP mesin X100-C
        $url = 'http://192.168.1.99/soap'; // Ganti dengan alamat SOAP endpoint mesin X100-C
        $this->client = new SoapClient($url, ['trace' => 1, 'exceptions' => 1]);
    }

    // Fungsi untuk mengambil data absensi
    public function getAttendanceData()
    {
        try {
            // Misalnya, menggunakan method 'GetAttendance' dari mesin
            $params = [];  // Parameter sesuai dengan dokumentasi mesin
            $response = $this->client->GetAttendance($params);
            return $response;
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
