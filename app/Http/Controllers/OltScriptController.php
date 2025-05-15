<?php

namespace App\Http\Controllers;

use App\Models\OltScript;
use Illuminate\Http\Request;

class OltScriptController extends Controller
{
    public function form()
    {
        return view('olt.form');
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'sn' => 'required',
            'port' => 'required',
            'slot' => 'required',
            'pon' => 'required',
            'number_onu' => 'required|numeric',
            'type_modem' => 'required',
            'nama_modem' => 'required',
            'tcont_profile' => 'required',
            'vlan' => 'required|numeric',
            'mode' => 'required',
            'pppoe_user' => 'nullable',
            'pppoe_pass' => 'nullable',
        ]);

        $interface = "gpon-olt_{$data['port']}/{$data['slot']}/{$data['pon']}";
        $onu_iface = "gpon-onu_{$data['port']}/{$data['slot']}/{$data['pon']}:{$data['number_onu']}";
        $mng_iface = "gpon-onu_{$data['port']}/{$data['slot']}/{$data['pon']}:{$data['number_onu']}";

        $script = "config t\n";
        $script .= "interface $interface\n";
        $script .= "onu {$data['number_onu']} type {$data['type_modem']} sn {$data['sn']}\nexit\n\n";

        $script .= "interface $onu_iface\n";
        $script .= "name {$data['nama_modem']}\n";
        $script .= "tcont 1 name INET profile {$data['tcont_profile']}\n";
        $script .= "gemport 1 name INET tcont 1\n";
        $script .= "service-port 1 vport 1 user-vlan {$data['vlan']} vlan {$data['vlan']}\nexit\n\n";

        $script .= "pon-onu-mng $mng_iface\n";
        $script .= "service INET gemport 1 vlan {$data['vlan']}\n";

        if ($data['mode'] === 'bridge') {
            $script .= "vlan port veip_1 mode hybrid\n";
        } else {
            $script .= "wan-ip 1 mode pppoe username {$data['pppoe_user']} password {$data['pppoe_pass']} vlan-profile PPPOE host 1\n";
            $script .= "wan 1 service internet host 1\n";
            $script .= "security-mgmt 212 state enable mode forward protocol web\n";
        }

        $script .= "end\nwr";


        // Simpan ke database
        $data['generated_script'] = $script;
        OltScript::create($data);

        return redirect()->back()->withInput()->with('script', $script);

    }

    ////

    public function commandReference()
    {
        return view('olt.command_reference');
    }

    public function formDetail()
    {
        $layout = 'layout_user'; // ganti sesuai layout kamu
        return view('olt.form_olt_detail', compact('layout'));
    }

    // Menangani form dan generate konfigurasi
    public function saveConfig(Request $request)
    {
        $data = $request->all();

        // Contoh generate script konfigurasi OLT (bisa dikembangkan lagi)
        $script = <<<EOT
config t
hostname {$data['nama_olt']}
username {$data['user_olt']} password {$data['password_olt']} privilege{$data['privilege']}
interface gpon-olt_{$data['port_slot_card']}{$data['sfp_pon']}
pon-onu-mng gpon-onu_{$data['port_slot_card']}{$data['sfp_pon']}:{$data['number_onu']}
sn {$data['sn_modem']}
vlan {$data['vlan']}
interface gei_{$data['port_slot_uplink']}{$data['interface_uplink']}
end
wr
EOT;

        $layout = 'layout_user'; // ganti sesuai layout kamu
        return view('olt.hasil_script', compact('script', 'layout'));
    }
}
