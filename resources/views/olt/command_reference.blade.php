@extends($layout)

@section('konten')
    <div class="container my-4">
        <h2 class="mb-4">Referensi Perintah OLT</h2>
        <div class="row row-cols-1 row-cols-md-2 g-4">

            @php
                $commands = [
                    ['title' => 'Melihat VLAN yang sudah terdaftar', 'cmd' => 'show vlan sum'],
                    ['title' => 'Melihat VLAN tertanam di mana', 'cmd' => 'show vlan 131'],
                    ['title' => 'Melihat settingan VLAN', 'cmd' => 'show running-config vlan 131'],
                    ['title' => 'Melihat settingan SFP/PON', 'cmd' => 'show running-config int gpon-olt_1/7/3'],
                    ['title' => 'Melihat settingan gpon-onu', 'cmd' => 'show running-config int gpon-onu_1/7/3:'],
                    ['title' => 'Melihat settingan pon-onu-mng', 'cmd' => 'show onu run con gpon-onu_1/7/3:'],
                    [
                        'title' => 'Melihat redaman di salah satu modem',
                        'cmd' => 'show pon power attenuation gpon-onu_1/7/3:',
                    ],
                    [
                        'title' => 'Melihat redaman semua modem di satu PON/SFP',
                        'cmd' => 'show pon power onu-rx gpon-olt_1/7/3',
                    ],
                    ['title' => 'Melihat SN modem di SFP/PON', 'cmd' => 'show gpon onu baseinfo gpon-olt_1/7/3'],
                    ['title' => 'Melihat info modem', 'cmd' => 'show gpon onu detail-info gpon-onu_1/7/3:'],
                    ['title' => 'Mereset modem', 'cmd' => "config t\npon-onu-mng gpon-onu_1/7/3:\nrestore factory"],
                    ['title' => 'Reboot modem', 'cmd' => "config t\npon-onu-mng gpon-onu_1/7/3:\nreboot"],
                    ['title' => 'Reboot OLT', 'cmd' => "reboot\nyes"],
                    ['title' => 'Ganti nama OLT', 'cmd' => "config t\nhostname Coba-NET\nend\nwr"],
                    ['title' => 'Tambah user OLT', 'cmd' => "config t\nusername zte password zte privilege15\nend\nwr"],
                    ['title' => 'Lihat user OLT', 'cmd' => "config t\nshow username"],
                    ['title' => 'Hapus user OLT', 'cmd' => "config t\nno username zte\nend\nwr"],
                    [
                        'title' => 'Hapus VLAN di uplink',
                        'cmd' => "config t\ninterface gei_1/4/3\nno switchport vlan 131\nend\nwr",
                    ],
                    ['title' => 'Hapus VLAN di OLT', 'cmd' => "config t\nno vlan 131\nend\nwr"],
                    [
                        'title' => 'Hapus modem yang sudah teregister',
                        'cmd' => "config t\ninterface gpon-olt_1/7/3\nno onu\nend\nwr",
                    ],
                ];
            @endphp

            @foreach ($commands as $item)
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item['title'] }}</h5>
                            <pre class="card-text">{{ $item['cmd'] }}</pre>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
