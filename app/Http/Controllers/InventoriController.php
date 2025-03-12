<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use App\Models\Inventori;
use App\Models\Inventory;

class InventoriController extends Controller
{
    public function index()
    {
        $tables = DB::select('SHOW TABLES');
        return view('tables.index', compact('tables'));
    }

    public function createTable(Request $request)
    {
        $validated = $request->validate([
            'table_name' => 'required|string',
            'columns' => 'required|array',
            'columns.*.name' => 'required|string',
            'columns.*.type' => 'required|string|in:string,integer,decimal,date'
        ]);

        $tableName = $validated['table_name'];

        // Periksa apakah tabel sudah ada
        if (Schema::hasTable($tableName)) {
            return response()->json(['error' => 'Table already exists!'], 400);
        }

        $columns = $validated['columns'];

        // Buat tabel baru
        Schema::create($tableName, function (Blueprint $table) use ($columns) {
            $table->id();
            foreach ($columns as $column) {
                $table->{$column['type']}($column['name']);
            }
            $table->timestamps();
        });

        // Simpan nama tabel ke database
        Inventory::create(['name' => $tableName]);

        return response()->json(['message' => 'Table created successfully!']);
    }


    // Get data from a table
    public function getTableData($table)
    {
        if (!Schema::hasTable($table)) {
            abort(404, 'Table not found.');
        }

        $data = DB::table($table)->get();
        return response()->json($data);
    }

    // Insert data into a table
    public function insertTableData(Request $request, $table)
    {
        if (!Schema::hasTable($table)) {
            abort(404, 'Table not found.');
        }

        $validated = $request->validate([
            'data' => 'required|array'
        ]);

        DB::table($table)->insert($validated['data']);
        return response()->json(['message' => 'Data inserted successfully!']);
    }

    public function showTableData($table)
    {
        // Periksa apakah tabel ada
        if (!Schema::hasTable($table)) {
            return response()->json(['error' => 'Table not found.'], 404);
        }

        // Ambil struktur tabel (kolom)
        $columns = Schema::getColumnListing($table);

        // Ambil data dari tabel
        $data = DB::table($table)->get();

        return response()->json([
            'table' => $table,
            'columns' => $columns,
            'data' => $data,
        ]);
    }



}
