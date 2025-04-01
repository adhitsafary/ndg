
<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AutomatisPaymentController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IsolirController;
use App\Http\Controllers\JumlahLainLainController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KasbonController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PelangganBayarSendiriController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PelangganOfController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PembayaranMudahController;
use App\Http\Controllers\PemberitahuanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\PSBController;
use App\Http\Controllers\RekapMutasiHarianController;
use App\Http\Controllers\RekapPemasanganController;
use App\Http\Controllers\RekapPemasanganControlller;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\UserController;
use App\Models\Pelanggan;
use App\Models\Pelangganof;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdapterController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BotTokenController;
use App\Http\Controllers\DataOdpController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\FingerprintController;
use App\Http\Controllers\GAController;
use App\Http\Controllers\GeneratorIdController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TanggalController;
use App\Http\Controllers\RandomNumberController;
use App\Http\Controllers\InventoriController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\KipControlller;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\ModemController;
use App\Http\Controllers\OdpController;
use App\Http\Controllers\PathcoreController;
use App\Http\Controllers\Pemasukan1Controller;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\RegisterPelangganBaruController;
use App\Http\Controllers\RekapMutasiController;
use App\Http\Controllers\SpinWheelController;
use App\Http\Controllers\TelegramBotController;
use App\Http\Controllers\X100Controller;
use App\Models\RegisterPelangganBaru;
use Illuminate\Support\Facades\Auth;

//Home asli
//Route::get('/home', [PelangganController::class, 'home'])->name('index');
Route::get('/home/tv/', [HomeController::class, 'home'])->name('home.index');
//Route::get('/login', [PerbaikanController::class, 'login'])->name('auth.login');

Route::get('/home/perbaikan/', [HomeController::class, 'perbaikan'])->name('home.perbaikan');
Route::get('/home/pemberitahuan/', [HomeController::class, 'pemberitahuan'])->name('home.pemberitahuan');
Route::get('/home/kehadiran/', [HomeController::class, 'kehadiran'])->name('home.kehadiran');
Route::get('/home/pemasangan/', [HomeController::class, 'pemasangan'])->name('home.pemasangan');
Route::get('/home/pemasukan/', [HomeController::class, 'pemasukan'])->name('home.pemasukan');
Route::get('/home/pengeluaran/', [HomeController::class, 'pengeluaran'])->name('home.pengeluaran');
Route::get('/home/jam/', [HomeController::class, 'jam'])->name('home.jam');
//Route::get('/home/grafik/', [HomeController::class, 'grafik'])->name('home.grafik');
Route::get('/home/isolir/', [HomeController::class, 'isolir'])->name('home.isolir');
Route::get('/home/isolir2/', [HomeController::class, 'isolir2'])->name('home.isolir2');
Route::get('/home/isolir3/', [HomeController::class, 'isolir3'])->name('home.isolir3');
//unutk teknisi
Route::get('/perbaikan', [PerbaikanController::class, 'index'])->name('perbaikan.index');
Route::get('/perbaikan/tiket/', [PerbaikanController::class, 'tiket_perbaikan'])->name('perbaikan.tiket');
//untukadmin
Route::get('/wo/create', [PerbaikanController::class, 'create_wo'])->name('psb.create');

Route::get('/perbaikan/create', [PerbaikanController::class, 'create'])->name('perbaikan.create');
Route::post('/psb/store', [PerbaikanController::class, 'store'])->name('psb.store');
Route::post('/perbaikan/store', [PerbaikanController::class, 'store'])->name('perbaikan.store');
Route::post('/wo/store', [PerbaikanController::class, 'store_wo'])->name('wo.store');
Route::get('/perbaikan/edit/{id}', [PerbaikanController::class, 'edit'])->name('perbaikan.edit');
Route::post('/perbaikan/update/{id}', [PerbaikanController::class, 'update'])->name('perbaikan.update');
Route::post('/perbaikan/hapus/{id}', [PerbaikanController::class, 'destroy'])->name('perbaikan.destroy');

Route::get('/perbaikan/export-pdf', [PerbaikanController::class, 'exportPdf'])->name('perbaikan.exportPdf');
Route::get('/perbaikan/export-excel', [PerbaikanController::class, 'exportExcel'])->name('perbaikan.exportExcel');

//coba
//Route::get('/pelanggancoba', [PelangganController::class, 'indexcoba'])->name('pelanggan.index');
//PELANGGAN
Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
Route::get('/pelanggan/reactivasi', [PelangganController::class, 'reactivasi'])->name('pelanggan.reactivasi');
Route::get('/pelanggan/isolir', [PelangganController::class, 'isolir'])->name('pelanggan.isolir');
Route::get('/pelanggan/unblock', [PelangganController::class, 'unblock'])->name('pelanggan.unblock');
Route::get('/pelanggan/block', [PelangganController::class, 'block'])->name('pelanggan.block');
Route::get('/pelanggan/psb', [PelangganController::class, 'psb'])->name('pelanggan.psb');
Route::get('/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
Route::post('/pelanggan/store', [PelangganController::class, 'store'])->name('pelanggan.store');
Route::get('/pelanggan/edit/{id}', [PelangganController::class, 'edit'])->name('pelanggan.edit');
Route::post('/pelanggan/update/{id}', [PelangganController::class, 'update'])->name('pelanggan.update');
Route::post('/pelanggan/hapus/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
Route::post('/pelanggan/{id}/isolir', [PelangganController::class, 'toIsolir'])->name('pelanggan.toIsolir');
Route::post('/pelanggan/{id}/unblock', [PelangganController::class, 'toUnblock'])->name('pelanggan.toUnblock');
Route::post('/pelanggan/{id}/toggle-status', [PelangganController::class, 'toggleStatus'])->name('pelanggan.toggleStatus');


Route::get('/pelanggan/offkan/{id}', [PelangganController::class, 'offkan'])->name('pelanggan.offkan');


//PSB
Route::get('/pasangbaru', [PSBController::class, 'index'])->name('pasangbaru.index');
Route::get('/pasangbaru/create', [PSBController::class, 'create'])->name('pasangbaru.create');
Route::post('/pasangbaru/store', [PSBController::class, 'store'])->name('pasangbaru.store');
Route::get('/pasangbaru/edit/{id}', [PSBController::class, 'edit'])->name('pasangbaru.edit');
Route::post('/pasangbaru/update/{id}', [PSBController::class, 'update'])->name('pasangbaru.update');
Route::post('/pasangbaru/hapus/{id}', [PSBController::class, 'destroy'])->name('pasangbaru.destroy');

//Detail
Route::get('/pelanggan/{id}/detail', [PelangganController::class, 'detail'])->name('pelanggan.detail');
Route::get('/pelanggan/off/{id}', [PelangganController::class, 'pelanggan_off'])->name('pelanggan.off');

//Pelangan Off
Route::get('/pelangganof', [PelangganOfController::class, 'index'])->name('pelangganof.index');
Route::get('/pelangganof/edit/{id}', [PelangganOfController::class, 'edit'])->name('pelangganof.edit');
Route::post('/pelangganof/update/{id}', [PelangganOfController::class, 'update'])->name('pelangganof.update');
Route::delete('/pelangganof/delete/{id}', [PelangganOfController::class, 'destroy'])->name('pelangganof.destroy');
Route::get('/pelangganof/{id}/detail', [PelangganOfController::class, 'detail'])->name('pelangganof.detail');
Route::get('/pelanggan/aktifkan/{id}', [PelangganOfController::class, 'aktifkan_pelanggan'])->name('aktifkan_pelanggan');


Route::post('/pelanggan/{id}/pembayaran', [PelangganController::class, 'pembayaran'])->name('pelanggan.pembayaran');
Route::patch('/pelanggan/{id}/toggle-visibility', [PelangganController::class, 'toggleVisibility'])->name('pelanggan.toggleVisibility');
Route::get('/pelanggan/{id}/history', [PelangganController::class, 'history'])->name('pelanggan.history');

//===========ini untuk pembayaran pelanggan sendiri sendiri =====================
Route::post('isolir/{id}/reactivate', [IsolirController::class, 'reactivatePelanggan'])->name('isolir.bayar');

//Aktifkan
Route::post('pelanggan/{id}/aktifkanPSB', [PelangganController::class, 'aktifkanPSB'])->name('pelanggan.aktifkanPSB');
Route::post('pelanggan/{id}/aktifkanReactivasi', [PelangganController::class, 'aktifkanReactivasi'])->name('pelanggan.aktifkanReactivasi');

Route::post('pelanggan/{id}/bayar', [PelangganController::class, 'bayar'])->name('pelanggan.bayar');
Route::post('pelanggan/{id}/bayar_mudah_hp', [PelangganController::class, 'bayar_mudah_hp_asli'])->name('pelanggan.bayar_mudah_hp');
//Route::post('isolir/{id}/bayar', [IsolirController::class, 'bayar'])->name('isolir.bayar');
Route::get('/pelanggan/{id}/historypembayaran', [PelangganController::class, 'historypembayaran'])->name('pelanggan.historypembayaran');
Route::get('/isolir/{id}/historypembayaran', [IsolirController::class, 'historypembayaran'])->name('isolir.historypembayaran');

//index pembayaran semua user  atau global
Route::get('/bayar-pelanggan/export/{format}', [PembayaranController::class, 'export'])->name('pembayaran.export');
Route::get('/pelanggan/export/{format}', [PelangganController::class, 'export'])->name('pelanggan.export');
Route::get('/pembayaran_hp/export/{format}', [PembayaranMudahController::class, 'export'])->name('pembayaran_hp.export');
Route::get('/pelanggan/export_isolir/{format}', [PelangganController::class, 'export_isolir'])->name('pelanggan.export_isolir');

//ini route hapus di detail pelanggan:
Route::post('/pembayaran-detail/hapus/{id}', [PembayaranController::class, 'destroy_detail_plg'])->name('detail_plg.destroy');

Route::post('/pembayaran/hapus/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');
Route::delete('/pembayaran/index/hapus/{id}', [PembayaranController::class, 'destroy_index'])->name('pembayaran_index.destroy');
Route::delete('/pembayaran/hp/hapus/{id}', [PembayaranController::class, 'destroy_hp'])->name('pembayaran_hp.destroy');



Route::get('/broadcast', [BroadcastController::class, 'index'])->name('broadcast.index');
Route::post('/broadcast/send', [BroadcastController::class, 'send'])->name('broadcast.send');
// Rute untuk menampilkan halaman pengiriman pesan
Route::get('/send-message', [MessageController::class, 'create'])->name('message.create');
// Rute untuk menyimpan pesan
Route::post('/send-message', [MessageController::class, 'store'])->name('message.store');
//Peringatan whatsaap
Route::get('/peringatan', [MessageController::class, 'peringatan'])->name('peringatan.create');
// Rute untuk menyimpan pesan
Route::post('/peringatan', [MessageController::class, 'store_peringatan'])->name('peringatan.store');

Route::get('/bot/rayuan/', [MessageController::class, 'rayuan'])->name('rayuan.create');
// Rute untuk menyimpan pesan
Route::post('/bot/rayuan/', [MessageController::class, 'store_rayuan'])->name('rayuan.store');

Route::get('/bot/perhatian/', [MessageController::class, 'perhatian'])->name('perhatian.create');
// Rute untuk menyimpan pesan
Route::post('/bot/perhatian/', [MessageController::class, 'store_perhatian'])->name('perhatian.store');

// Rute untuk Bot Khusus Tiara
Route::get('/bot/tiara/', [MessageController::class, 'tiara'])->name('tiara.create');
Route::post('/bot/tiara/', [MessageController::class, 'store_tiara'])->name('tiara.store');

// Rute untuk Bot Khusus Pelanggan OFF
Route::get('/bot/plg_of/', [MessageController::class, 'plg_of'])->name('plg_of.create');
Route::post('/bot/plg_of/', [MessageController::class, 'store_plg_of'])->name('plg_off.store');

// Rute untuk Bot Khusus Pelanggan OFF
Route::get('/bot/bayar25/', [MessageController::class, 'bayar25'])->name('bayar25.create');
Route::post('/bot/bayar25/', [MessageController::class, 'store_bayar25'])->name('bayar25.store');

Route::get('/bot/promo_tgl25/', [MessageController::class, 'promo_tgl25'])->name('promo_tgl25.create');
Route::post('/bot/promo_tgl25/', [MessageController::class, 'store_promo_tgl25'])->name('promo_tgl25.store');

Route::get('/bot/ucapan_id2025/', [MessageController::class, 'ucapan_id2025'])->name('ucapan_id2025.create');
Route::post('/bot/ucapan_id2025/', [MessageController::class, 'store_ucapan_id2025'])->name('ucapan_id2025.store');


//PEMBAYARAN GLOBAL
Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
Route::get('/pembayaran_hp', [PembayaranController::class, 'pembayaran_hp'])->name('pembayaran.pembayaran_hp');
//chart bulanan
Route::get('/dashboard', [PelangganController::class, 'getMonthlyPayments']);

//midleware
Route::middleware(['guest'])->group(function () {
    // Rute untuk halaman login
    Route::get('/', [SesiController::class, 'index'])->name('login');
    Route::post('/', [SesiController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {
    // Redirect berdasarkan role
    Route::get('/redirect', [SesiController::class, 'redirectUser'])->name('redirect');

    // Logout
    Route::get('/logout', [SesiController::class, 'logout'])->name('logout');
});

//ini baru 19/12/2024
// Arahkan teknisi ke halaman absensi/index
Route::middleware(['auth', 'role:teknisi'])->get('/absensi/index', function () {
    return view('absensi.index'); // Halaman absensi untuk teknisi
});



Route::get('/teknisi/baru', [TeknisiController::class, 'index'])->name('teknisi');
//Route::get('/homebaru', [PelangganController::class, 'home'])->name('index');

Route::middleware(['auth'])->group(function () {
    // Rute teknisi
    Route::get('/masuk/teknisi', [TeknisiController::class, 'index'])
        ->middleware('userAkses:teknisi,superadmin') // Superadmin bisa akses teknisi
        ->name('teknisi.index');

    // Rute admin
    Route::get('/masuk/admin', [AdminController::class, 'home'])
        ->middleware('userAkses:admin,superadmin'); // Superadmin bisa akses admin

    // Rute admin
    Route::get('/masuk/finance', [FinanceController::class, 'home'])
        ->middleware('userAkses:finance,superadmin'); // Superadmin bisa akses admin

    // Rute superadmin
    Route::get('/masuk/superadmin', [SuperAdminController::class, 'home'])
        ->middleware('userAkses:superadmin');

    // Logout
    Route::get('/logout', [SesiController::class, 'logout'])->name('logout');
});

Route::get('/masuk/teknisi', [TeknisiController::class, 'index'])->name('teknisi.index');
Route::get('coba', [TeknisiController::class, 'coba']);
Route::get('/pelanggan/unpaid', [PelangganController::class, 'belumBayar'])->name('pelanggan.unpaid');
Route::get('/cekdulu', [CobaController::class, 'create']);
//landing page
Route::get('/home2', [PerbaikanController::class, 'home2']);
Route::get('/search-pelanggan', [PerbaikanController::class, 'searchPelanggan'])->name('pelanggan.search');
Route::get('/get-pelanggan/{id}', [PerbaikanController::class, 'getPelanggan'])->name('pelanggan.get');
Route::get('/rekap-teknisi', [PerbaikanController::class, 'rekapTeknisi'])->name('perbaikan.rekapTeknisi');

Route::get('/teknisi/rekap-teknisi', [TeknisiController::class, 'rekapTeknisi'])->name('teknisi.rekap_teknisi');
Route::post('/rekap-teknisi/print', [PerbaikanController::class, 'printRekapTeknisi'])->name('perbaikan.printRekapTeknisi');
Route::post('/perbaikan/{id}/selesai', [PerbaikanController::class, 'selesai'])->name('perbaikan.selesai');
Route::post('/psb/{id}/selesai', [RekapPemasanganController::class, 'selesai'])->name('psb.selesai');

Route::get('/cari/teknisi', [TeknisiController::class, 'cari'])->name('cari.teknisi');
//Route::get('/pembayaran/admin', [PembayaranMudahController::class, 'admin'])->name('pembayaran_mudah.admin');

//Alamat Karyawan
Route::get('/masuk/superadmin/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
Route::get('/masuk/superadmin/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
Route::post('/masuk/superadmin/karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
Route::get('/masuk/superadmin/karyawan/edit/{id}', [KaryawanController::class, 'edit'])->name('karyawan.edit');
Route::post('/masuk/superadmin/karyawan/update/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
Route::delete('/masuk/superadmin/karyawan/delete/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
Route::get('/masuk/superadmin/karyawan/{id}/detail', [KaryawanController::class, 'detail'])->name('karyawan.detail');
Route::get('/masuk/superadmin/karyawan/aktifkan/{id}', [KaryawanController::class, 'showOff'])->name('karyawan.non_aktifkan');
//qr karyawan
Route::get('/karyawa', [KaryawanController::class, 'qr_code'])->name('karyawan.qr_code');
Route::get('/k/detail/{id}', [KaryawanController::class, 'detail_qr'])->name('karyawan.detail_qr');

//Alamat Kasbon
Route::get('/masuk/superadmin/karyawan/kasbon', [KasbonController::class, 'index'])->name('kasbon.index');
Route::get('/masuk/superadmin/karyawan/{id}/kasbon/create', [KasbonController::class, 'create'])->name('kasbon.create');
Route::post('/masuk/superadmin/karyawan/kasbon/store', [KasbonController::class, 'store'])->name('kasbon.store');
Route::get('/masuk/superadmin/karyawan/kasbon/edit/{id}', [KasbonController::class, 'edit'])->name('kasbon.edit');
Route::post('/masuk/superadmin/karyawan/kasbon/update/{id}', [KasbonController::class, 'update'])->name('kasbon.update');
Route::delete('/masuk/superadmin/karyawan/kasbon/delete/{id}', [KasbonController::class, 'destroy'])->name('kasbon.destroy');

//pengeluaran lainya
Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
Route::get('/pengeluaran/create', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
Route::post('/pengeluaran/store', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
Route::get('/pengeluaran/edit/{id}', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
Route::post('/pengeluaran/update/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
Route::post('/pengeluaran/hapus/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
//index_jml
Route::get('/pengeluaran/index_jml', [PengeluaranController::class, 'index_jml'])->name('pengeluaran.index_jml');

//Rekap Pemasangan
Route::get('/rekap_pemasangan', [RekapPemasanganController::class, 'index'])->name('rekap_pemasangan.index');
Route::get('/rekap_pemasangan/create', [RekapPemasanganController::class, 'create'])->name('rekap_pemasangan.create');
Route::get('/rekap_pemasangan/aktivasi/{id}', [RekapPemasanganController::class, 'aktivasi'])->name('rekap_pemasangan.aktivasi');
Route::post('/rekap_pemasangan/store', [RekapPemasanganController::class, 'store'])->name('rekap_pemasangan.store');
Route::get('/rekap_pemasangan/edit/{id}', [RekapPemasanganController::class, 'edit'])->name('rekap_pemasangan.edit');
Route::post('/rekap_pemasangan/update/{id}', [RekapPemasanganController::class, 'update'])->name('rekap_pemasangan.update');
Route::post('/rekap_pemasangan/hapus/{id}', [RekapPemasanganController::class, 'destroy'])->name('rekap_pemasangan.destroy');

//pemasukan lainya
Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('pemasukan.index');
Route::get('/pemasukan/create', [PemasukanController::class, 'create'])->name('pemasukan.create');
Route::post('/pemasukan/store', [PemasukanController::class, 'store'])->name('pemasukan.store');
Route::get('/pemasukan/edit/{id}', [PemasukanController::class, 'edit'])->name('pemasukan.edit');
Route::post('/pemasukan/update/{id}', [PemasukanController::class, 'update'])->name('pemasukan.update');
Route::post('/pemasukan/hapus/{id}', [PemasukanController::class, 'destroy'])->name('pemasukan.destroy');
//index_jml
Route::get('/pemasukan/index_jml', [PemasukanController::class, 'index_jml'])->name('pemasukan.index_jml');




// Routes untuk isolir

//Isolir
Route::get('/isolir', [IsolirController::class, 'index'])->name('isolir.index');
Route::get('/isolir/edit/{id}', [IsolirController::class, 'edit'])->name('isolir.edit');
Route::post('/isolir/update/{id}', [IsolirController::class, 'update'])->name('isolir.update');
Route::delete('/isolir/delete/{id}', [IsolirController::class, 'destroy'])->name('isolir.destroy');
Route::get('/isolir/{id}/detail', [IsolirController::class, 'detail'])->name('isolir.detail');
Route::get('/isolir/aktifkan/{id}', [IsolirController::class, 'showOff'])->name('isolir.aktifkan_pelanggan');

// web.php
//Route::post('/isolir/reactivate/{id}', [IsolirController::class, 'reactivatePelanggan'])->name('pelanggan.reactivate');


Route::post('/isolir/{id}/activate', [IsolirController::class, 'activate'])->name('isolir.activate');
Route::get('/isolir/cleanup', [IsolirController::class, 'cleanUp'])->name('isolir.cleanup');

// web.php
Route::post('pelanggan/to-isolir/{id}', [PelangganController::class, 'toIsolir'])->name('pelanggan.toIsolir1');
// web.php
Route::post('pelanggan/to-off/{id}', [IsolirController::class, 'toOff'])->name('pelanggan.toOff');

//rekap mutasi harian
Route::get('/rekap-mutasi-harian', [RekapMutasiHarianController::class, 'index'])->name('rekap.mutasi.harian');


Route::post('pelanggan/{id}/update-status', [PelangganController::class, 'updateStatus'])->name('pelanggan.updateStatus');
Route::get('/rekap-harian', [JumlahLainLainController::class, 'lihatRekapHarian'])->name('rekap-harian');
//filter pelanggan harian tgl_tagih_plg
Route::get('/pelanggan/tagihan', [PelangganController::class, 'filterByTanggalTagih'])->name('pelanggan.filterTagih');
//filter di index  pelanggan
Route::get('/pelanggan/filter-tagih', [PelangganController::class, 'filterByTanggalTagihindex'])->name('pelanggan.filterTagihindex');
Route::get('/pelanggan/tagihan/index', [PelangganController::class, 'filterByTanggalTagihindex'])->name('pelanggan.filterTagihindex');
//filter di pembayaran
Route::get('/pembayaran/filter/', [PembayaranController::class, 'index'])->name('pembayaran.filter');
//filter di isolir
Route::get('/isolir/tagihan/index', [IsolirController::class, 'filterByTanggalTagihindex'])->name('isolir.filterTagihindex');
//filter pelanggan Off
Route::get('/pelanggan/tagihan/index', [PelangganOfController::class, 'filterByTanggalTagihindex'])->name('pelangganof.filterTagihindex');
//isolir asli
Route::get('/check-isolir', [PelangganController::class, 'checkAndMoveToIsolir'])->name('check.isolir');
//cek payment asli
Route::get('/update-payment-status', [PelangganController::class, 'updatePaymentStatus'])->name('update.payment.status');
//reactive bayar
Route::post('/reactivate-bayar', [IsolirController::class, 'reactivateAndBayar'])->name('pelanggan.reactivateAndBayar');
//pelanggan bayar
Route::get('/pembayaran/csbayar', [PelangganBayarSendiriController::class, 'index'])->name('pembayaran.csbayar');
Route::get('/costumer', [PelangganBayarSendiriController::class, 'index'])->name('costumer.index');

// Route untuk admin
// Route::middleware(['role:admin'])->group(function () {
//   Route::get('/masuk/admin', [AdminController::class, 'index']);
//   Route::get('/masuk/admin/karyawan', [AdminController::class, 'karyawan']);
// });

// Route untuk superadmin dengan akses hanya ke sub-route tertentu
// Route::middleware(['role:superadmin,masuk/superadmin/karyawan'])->group(function () {
//  Route::get('/masuk/superadmin/karyawan', [SuperAdminController::class, 'karyawan']);
// });

// Route untuk teknisi
// Route::middleware(['role:teknisi'])->group(function () {
//   Route::get('/masuk/teknisi', [TeknisiController::class, 'index']);
//});

Route::get('/pindahroute', function () {
    return view('pindahroute');
});

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}/update', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('/pembayaran/edit/{id}', [PembayaranController::class, 'edit'])->name('pembayaran.edit');
Route::get('/pembayaran-detail/edit/{id}', [PembayaranController::class, 'edit'])->name('pembayaran_detail.edit');
Route::put('/pembayaran/update/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');

//redirect ketika btn / data di klik di home index
Route::get('/pelanggan/redirect', [HomeController::class, 'redirectToPelanggan'])->name('pelanggan.redirect');
Route::get('/pelanggan/sudahbayar', [HomeController::class, 'showPelangganBelumBayar'])->name('pelanggan.belumbayar');
Route::get('/pelanggan/belumbayar', [HomeController::class, 'showPelangganSudahBayar'])->name('pelanggan.sudahbayar');
Route::get('/pelanggan/historyhariini', [HomeController::class, 'historyhariini'])->name('pelanggan.historyhariini');

Route::get('/target', [TargetController::class, 'index'])->name('target.index');
Route::post('/simpan-target', [TargetController::class, 'store'])->name('target.store');
Route::post('/target/update/{id}', [TargetController::class, 'update'])->name('target.update');
Route::delete('/target/{id}', [TargetController::class, 'destroy'])->name('target.destroy');

//file Storage
Route::get('/file/index', [FileController::class, 'index'])->name('file.index');
Route::get('/file/create', [FileController::class, 'create'])->name('file.create');
Route::post('/file/store', [FileController::class, 'store'])->name('file.store');
Route::get('/file/edit/{id}', [FileController::class, 'edit'])->name('file.edit');
Route::post('/file/update/{id}', [FileController::class, 'update'])->name('file.update');
Route::post('/file/hapus/{id}', [FileController::class, 'destroy'])->name('file.destroy');
Route::get('/file/download/{id}', [FileController::class, 'download'])->name('file.download');
//Route::get('/file/{path?}', [FileController::class, 'index'])->name('file.index')->where('path', '.*');

//Pemberitahuan
Route::get('/pemberitahuan', [PemberitahuanController::class, 'index'])->name('pemberitahuan.index');
Route::get('/pemberitahuan/create', [PemberitahuanController::class, 'create'])->name('pemberitahuan.create');
Route::post('/pemberitahuan/store', [PemberitahuanController::class, 'store'])->name('pemberitahuan.store');
Route::get('/pemberitahuan/edit/{id}', [PemberitahuanController::class, 'edit'])->name('pemberitahuan.edit');
Route::post('/pemberitahuan/update/{id}', [PemberitahuanController::class, 'update'])->name('pemberitahuan.update');
Route::post('/pemberitahuan/hapus/{id}', [PemberitahuanController::class, 'destroy'])->name('pemberitahuan.destroy');

//Automatis Bayar
Route::get('/pelanggan/automatispayment', [AutomatisPaymentController::class, 'index'])->name('automatispayment.index');
Route::get('/pelanggan/{id}/payment', [AutomatisPaymentController::class, 'payment'])->name('pelanggan.payment');
Route::post('/pelanggan/{id}/process-payment', [AutomatisPaymentController::class, 'processPayment'])->name('pelanggan.processPayment');

//Pemabayaran Mudah
Route::get('/pembayaran/mudah', [PembayaranMudahController::class, 'index'])->name('pembayaran_mudah.index');
Route::get('/pembayaran/admin', [PembayaranMudahController::class, 'admin'])->name('pembayaran_mudah.admin');
Route::get('/pembayaran/mudah/coba', [PembayaranMudahController::class, 'coba'])->name('pembayaran_mudah.coba');
Route::get('/pembayaran/mudah/bayar_hp', [PembayaranMudahController::class, 'bayar_hp'])->name('pembayaran_mudah.bayar_hp');

//absensi
Route::post('/absensi', [AbsensiController::class, 'store']);
Route::get('/hitung-gaji/{user_id}', [AbsensiController::class, 'hitungGaji']);
Route::get('/form-absensi', [AbsensiController::class, 'showAbsensiForm']);
Route::get('/form-gaji', [AbsensiController::class, 'showGajiForm']);

Route::get('/absensi/splash', [AbsensiController::class, 'splash'])->name('absensi.splash');
//Route::get('/absensi/login', [AbsensiController::class,   'login'])->name('absensi.login');
Route::get('/absensi/absen', [AbsensiController::class, 'absen'])->name('absensi.absen');

Route::get('/absensi/dashboard', [AbsensiController::class, 'getAbsensiData'])->name('absensi.dashboard');

//midleware
Route::middleware(['guest'])->group(function () {
    // Rute untuk halaman login
    Route::get('/absensi/index', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/index', [AbsensiController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/absensi/masuk', [AbsensiController::class, 'store']);
    Route::post('/absensi/pulang', [AbsensiController::class, 'updatePulang']);
});

Route::post('/absensi/hapus/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');

//Payment Notifiaksi ke telegram
Route::post('/store-payment', [NotificationController::class, 'storePayment'])->name('store.payment');
Route::get('/send-notification', [NotificationController::class, 'notifyLatesPayment'])->name('send.notification');


Route::get('/ubah-tanggal', [TanggalController::class, 'index'])->name('ubah-tanggal.index');
Route::post('/ubah-tanggal/update', [TanggalController::class, 'update'])->name('ubah-tanggal.update');

Route::get('/index/number/', [RandomNumberController::class, 'index'])->name('random_numbers.index');
Route::post('/number/generate/', [RandomNumberController::class, 'generate'])->name('random_numbers.generate');
Route::delete('/number/{id}/delete', [RandomNumberController::class, 'delete'])->name('random_numbers.delete');
Route::post('/random_numbers/update/{id}', [RandomNumberController::class, 'update'])->name('random_numbers.update');

Route::get('/tables', [InventoriController::class, 'index']);
Route::post('/tables/create', [InventoriController::class, 'createTable']);
Route::get('/tables/{table}/data', [InventoriController::class, 'getTableData']);
Route::post('/tables/{table}/data', [InventoriController::class, 'insertTableData']);

Route::get('/tables/{table}/data', [InventoriController::class, 'showTableData']);

Route::get('/modem', [ModemController::class, 'index'])->name('modem.index');
Route::get('/modem_hp', [ModemController::class, 'index_hp'])->name('modem_hp.index');
Route::get('/modem/create', [ModemController::class, 'create'])->name('modem.create');
Route::post('/modem', [ModemController::class, 'store'])->name('modem.store');
Route::get('/modem/{modem}/edit', [ModemController::class, 'edit'])->name('modem.edit');
Route::put('/modem/{modem}', [ModemController::class, 'update'])->name('modem.update');
Route::post('/modem/{modem}', [ModemController::class, 'destroy'])->name('modem.destroy');

Route::get('/generator', [GeneratorIdController::class, 'index'])->name('generator_id.index');
Route::get('/generator_hp', [GeneratorIdController::class, 'index_hp'])->name('generator_id_hp.index');
Route::get('/generator/create', [GeneratorIdController::class, 'create'])->name('generator_id.create');
Route::post('/generator', [GeneratorIdController::class, 'store'])->name('generator_id.store');
Route::get('/generator/{generator}/edit', [GeneratorIdController::class, 'edit'])->name('generator_id.edit');
Route::put('/generator/{generator}', [GeneratorIdController::class, 'update'])->name('generator_id.update');
Route::post('/generator/{generator}', [GeneratorIdController::class, 'destroy'])->name('generator_id.destroy');
//Route::post('/generator/hapus/{id}', [GeneratorIdController::class, 'destroy'])->name('generator_id.destroy');

Route::get('/adapter', [AdapterController::class, 'index'])->name('adapter.index');
Route::get('/adapter_hp', [AdapterController::class, 'index_hp'])->name('adapter_hp.index');
Route::get('/adapter/create', [AdapterController::class, 'create'])->name('adapter.create');
Route::post('/adapter', [AdapterController::class, 'store'])->name('adapter.store');
Route::get('/adapter/{adapter}/edit', [AdapterController::class, 'edit'])->name('adapter.edit');
Route::put('/adapter/{adapter}', [AdapterController::class, 'update'])->name('adapter.update');
Route::post('/adapter/{adapter}', [AdapterController::class, 'destroy'])->name('adapter.destroy');


Route::get('/pathcore', [PathcoreController::class, 'index'])->name('pathcore.index');
Route::get('/adapter_hp', [PathcoreController::class, 'index_hp'])->name('pathcore_hp.index');
Route::get('/adapter/create', [PathcoreController::class, 'create'])->name('pathcore.create');
Route::get('/adapter', [PathcoreController::class, 'store'])->name('pathcore.store');
Route::get('/adapter/{adapter}/edit', [PathcoreController::class, 'edit'])->name('pathcore.edit');


Route::get('/odp', [OdpController::class, 'index'])->name('odp.index');

// Rute untuk menampilkan detail ODP (melihat pelanggan berdasarkan kode_odp)
Route::get('/odp/detail/{kode_odp}', [OdpController::class, 'show'])->name('odp.show');

// Rute untuk menampilkan form tambah ODP
Route::get('/odp/create', [OdpController::class, 'create'])->name('odp.create');

// Rute untuk menyimpan ODP baru
Route::post('/odp', [OdpController::class, 'store'])->name('odp.store');

// Rute untuk menampilkan form edit ODP
Route::get('/odp/{id}/edit', [OdpController::class, 'edit'])->name('odp.edit');

// Rute untuk memperbarui data ODP
Route::put('/odp/{id}', [OdpController::class, 'update'])->name('odp.update');

// Rute untuk menghapus ODP
Route::delete('/odp/{id}', [OdpController::class, 'destroy'])->name('odp.destroy');

Route::post('/update-odp/{id_plg}', [PelangganController::class, 'updateODP'])->name('update.odp');

Route::post('/webhook', [TelegramBotController::class, 'webhook']);



Route::get('/data-maps-pelanggan', [DataOdpController::class, 'maps_pelanggan'])->name('data-odp.maps_pelanggan');

Route::get('/data-odp', [DataOdpController::class, 'index'])->name('data-odp.index');
Route::get('/data-odp/create', [DataOdpController::class, 'create'])->name('data-odp.create');
Route::post('/data-odp', [DataOdpController::class, 'store'])->name('data-odp.store');
Route::get('/data-odp/{data_odp}/edit', [DataOdpController::class, 'edit'])->name('data-odp.edit');
Route::put('/data-odp/{data_odp}', [DataOdpController::class, 'update'])->name('data-odp.update');
Route::delete('/data-odp/{data_odp}', [DataOdpController::class, 'destroy'])->name('data-odp.destroy');

Route::resource('bot_tokens', BotTokenController::class);


// Rute untuk pencarian pelanggan dengan Select2
Route::get('/search-pelanggan', [GeneratorIdController::class, 'searchPelanggan']);

// Rute untuk mendapatkan detail pelanggan berdasarkan ID
Route::get('/get-pelanggan/{id}', [GeneratorIdController::class, 'getPelanggan']);

Route::get('/odp/desa/{desa}', [OdpController::class, 'showByDesa'])->name('odp.showByDesa');

Route::get('/mutasi', [RekapMutasiController::class, 'index'])->name('mutasi.index');

Route::get('/x100c', [X100Controller::class, 'ambilData'])->name('x100c.index2');

Route::get('/x100c/index/', [X100Controller::class, 'index'])->name('x100c.index3');

Route::get('/x100c/show', [X100Controller::class, 'index_baru'])->name('x100c.show');
Route::get('x100c/detail/{nama}/{pin}', [X100Controller::class, 'detail'])->name('x100c.detail');
Route::get('/slip-gaji/{nama}/{pin}', [X100Controller::class, 'slipGaji'])->name('slipGaji');
Route::get('/attendance', [FingerprintController::class, 'getAttendance']);


Route::get('/log-absensi', [X100Controller::class, 'index'])->name('x100c.index');
Route::get('/download-log', [X100Controller::class, 'downloadLog'])->name('x100c.downloadLog');

Route::get('/ambil-data', [X100Controller::class, 'ambilData'])->name('x100c.ambilData');

Route::delete('/x100c/detail/delete/{id}', [X100Controller::class, 'destroy'])->name('x100c.destroy');

Route::get('/modem/export', [ModemController::class, 'exportExcel'])->name('modem.export');


Route::get('/modem/search', [ModemController::class, 'search'])->name('modem.search');


//Route::post('/midtrans/payment/{pelangganId}', [MidtransController::class, 'createTransaction']);

//Route::post('/pembayaran/proses/{id}', [PembayaranController::class, 'prosesPembayaran'])->name('pembayaran.proses');

Route::post('/create-payment', [MidtransController::class, 'createPayment'])->name('create.payment');

Route::post('/payment-notification', [MidtransController::class, 'paymentNotification']);

Route::get('/alat/hitung-rasio', [AlatController::class, 'hitung_rasio'])->name('alat.hitung_rasio');

Route::get('/pemasukan1/index', [Pemasukan1Controller::class, 'index'])->name('pemasukan1.index');
Route::get('/pemasukan1/create/', [Pemasukan1Controller::class, 'create'])->name('pemasukan1.create');

Route::get('/pemasukan/export-excel', [PemasukanController::class, 'exportExcel'])->name('pemasukan.exportExcel');
Route::get('/pemasukan/export-pdf', [PemasukanController::class, 'exportPdf'])->name('pemasukan.exportPdf');

Route::get('/pengeluaran/export-excel', [PengeluaranController::class, 'exportExcel'])->name('pengeluaran.exportExcel');
Route::get('/pengeluaran/export-pdf', [PengeluaranController::class, 'exportPdf'])->name('pengeluaran.exportPdf');

Route::get('/redirect', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect('/login'); // Jika belum login, arahkan ke halaman login
    }

    // Redirect berdasarkan role user
    switch ($user->role) {
        case 'superadmin':
            return redirect('/masuk/superadmin');
        case 'admin':
            return redirect('/masuk/admin');
        case 'finance':
            return redirect('/masuk/finance');
        case 'teknisi':
            return redirect('/masuk/teknisi');
        default:
            return redirect('/login'); // Jika role tidak dikenali, kembali ke login
    }
})->middleware('auth');

Route::resource('inventory', InventoryController::class);

Route::get('/perbaikan/lihat/{id}', [PerbaikanController::class, 'show'])->name('perbaikan.show');

Route::get('/perbaikan/{id}/pengembalian', [InventoryController::class, 'showReturnForm'])->name('inventory.returnForm');
Route::post('/perbaikan/{id}/pengembalian', [InventoryController::class, 'processReturn'])->name('inventory.processReturn');

Route::get('/perbaikan/print/{id}', [PerbaikanController::class, 'print'])->name('perbaikan.print');

Route::get('/psb/lihat/{id}', [RekapPemasanganController::class, 'show'])->name('psb.show');
Route::get('/psb/{id}/pengembalian', [InventoryController::class, 'showReturnForm_psb'])->name('inventory.returnForm_psb');
Route::post('/psb/{id}/pengembalian', [InventoryController::class, 'processReturn_psb'])->name('inventory.processReturn_psb');

Route::get('/psb/print/{id}', [RekapPemasanganController::class, 'print_psb'])->name('rekap_pemasangan.print');

Route::get('/kip', [KipControlller::class, 'index'])->name('kip.index');
Route::get('/kip/{id}', [KipControlller::class, 'show'])->name('kip.show');

Route::get('/ga', [GAController::class, 'index'])->name('ga.index');

Route::get('/spin', [SpinWheelController::class, 'index'])->name('spin.index');
Route::get('/spin/create', [SpinWheelController::class, 'create'])->name('spin.create');
Route::post('/spin', [SpinWheelController::class, 'store'])->name('spin.store');

Route::resource('registerpelangganbaru', RegisterPelangganBaruController::class);

Route::get('daptar-pelanggan-baru', [RegisterPelangganBaruController::class, 'index_pelanggan'])->name('index_pelanggan');
//Route::get('pesan', [PesanController::class, 'index'])->name('pesan.index');

Route::resource('pesan', PesanController::class);


Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
Route::post('/backup', [BackupController::class, 'backup'])->name('backup.run');
