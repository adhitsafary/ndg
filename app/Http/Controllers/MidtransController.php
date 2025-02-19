<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\MidtransException;

class MidtransController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$clientKey = config('midtrans.client_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function createPayment(Request $request)
{
    $orderId = uniqid(); // ID order unik
    $transactionDetails = [
        'order_id' => $orderId,
        'gross_amount' => $request->amount, // Pastikan amount ada di request
    ];

    $customerDetails = [
        'first_name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
    ];

    try {
        // Membuat token Snap
        $snapToken = Snap::getSnapToken([
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
        ]);

        // Redirect ke view payment.blade.php dengan membawa snap_token
        return view('payment', ['snap_token' => $snapToken]);
    } catch (MidtransException $e) {
        // Menangani error Midtrans
        return response()->json(['error' => $e->getMessage()]);
    }
}


    // Method untuk verifikasi pembayaran
    public function paymentNotification(Request $request)
    {
        $notification = $request->all();

        // Mengirimkan notifikasi pembayaran ke Midtrans
        $status = \Midtrans\Notification::status($notification['order_id']);
        // Logic untuk memproses status pembayaran

        return response()->json(['status' => $status]);
    }
}
