<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Pelanggan;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/pelanggan/{id}', function ($id) {
    $pelanggan = \App\Models\Pelanggan::find($id);

    if (!$pelanggan) {
        return response()->json(['error' => 'Pelanggan tidak ditemukan'], 404);
    }
    

    return response()->json($pelanggan);
});
