<?php

namespace App\Http\Controllers;

use App\Models\GeneratorId;
use Illuminate\Http\Request;

class GeneratorIdController extends Controller
{
    public function index()
    {
        $generatorIds = GeneratorId::all();
        return view('generator_id.index', compact('generatorIds'));
    }

    public function index_hp()
    {
        $generatorIds = GeneratorId::all();
        return view('generator_id.index_hp', compact('generatorIds'));
    }

    public function create()
    {
        return view('generator_id.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_perusahaan' => 'required|max:4',
            'kode_tahun' => 'required|max:2',
            'kode_nik' => 'required|max:4',
            'kode_odp' => 'required|max:2',
        ]);

        GeneratorId::create($request->all());

        return redirect()->route('generator_id.index')
                         ->with('success', 'Generator ID created successfully.');
    }



    public function edit(GeneratorId $generatorId)
    {
        return view('generator_id.edit', compact('generatorId'));
    }

    public function update(Request $request, GeneratorId $generatorId)
    {
        $request->validate([
            'kode_perusahaan' => 'required|max:4',
            'kode_tahun' => 'required|max:2',
            'kode_nik' => 'required|max:4',
            'kode_odp' => 'required|max:2',
        ]);

        $generatorId->update($request->all());

        return redirect()->route('generator_id.index')
                         ->with('success', 'Generator ID updated successfully.');
    }

    public function destroy(GeneratorId $generatorId)
    {
        $generatorId->delete();

        return redirect()->route('generator_id.index')
                         ->with('success', 'Generator ID deleted successfully.');
    }
}

