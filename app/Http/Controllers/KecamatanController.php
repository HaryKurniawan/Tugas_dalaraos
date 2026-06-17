<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Services\KecamatanService;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    protected $kecamatanService;

    public function __construct(KecamatanService $kecamatanService)
    {
        $this->kecamatanService = $kecamatanService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kecamatans = $this->kecamatanService->getAll();
        return view('admin.kecamatan.index', compact('kecamatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kecamatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'ongkir' => 'required|numeric|min:0',
        ]);

        $this->kecamatanService->create($validated);

        return redirect()->route('admin.kecamatan.index')
            ->with('success', 'Data kecamatan dan ongkir berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kecamatan $kecamatan)
    {
        $kecamatan->load('ongkir');
        return view('admin.kecamatan.edit', compact('kecamatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kecamatan $kecamatan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'ongkir' => 'required|numeric|min:0',
        ]);

        $this->kecamatanService->update($kecamatan, $validated);

        return redirect()->route('admin.kecamatan.index')
            ->with('success', 'Data kecamatan dan ongkir berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kecamatan $kecamatan)
    {
        $this->kecamatanService->delete($kecamatan);

        return redirect()->route('admin.kecamatan.index')
            ->with('success', 'Data kecamatan berhasil dihapus.');
    }
}
