<?php

namespace App\Http\Controllers;

use App\Models\ProdukPos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProdukPosController extends Controller
{
    public function index(Request $request)
    {
        $query = ProdukPos::query();

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $produk_pos = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.produk_pos.index', compact('produk_pos'));
    }

    public function create()
    {
        return view('admin.produk_pos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku'        => 'required|string|max:50|unique:produk_pos,sku',
            'nama_produk'=> 'required|string|max:255',
            'harga'      => 'required|numeric|min:0',
            'kategori'   => 'required|string|max:100',
            'deskripsi'  => 'nullable|string',
            'gambar_url' => 'nullable|url|max:2048',
            'gambar'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('produk_pos', 'public');
        } elseif ($request->filled('gambar_url')) {
            $validated['gambar'] = $request->input('gambar_url');
        } else {
            unset($validated['gambar']);
        }

        ProdukPos::create($validated);

        return redirect()->route('admin.produk-pos.index')
                         ->with('success', 'Produk POS berhasil ditambahkan!');
    }

    public function edit(ProdukPos $produkPo)
    {
        // Parameter automatically injected as $produkPo based on singular of produk-pos
        return view('admin.produk_pos.edit', ['produk_pos' => $produkPo]);
    }

    public function update(Request $request, ProdukPos $produkPo)
    {
        $validated = $request->validate([
            'sku'        => 'required|string|max:50|unique:produk_pos,sku,' . $produkPo->id,
            'nama_produk'=> 'required|string|max:255',
            'harga'      => 'required|numeric|min:0',
            'kategori'   => 'required|string|max:100',
            'deskripsi'  => 'nullable|string',
            'gambar_url' => 'nullable|url|max:2048',
            'gambar'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($produkPo->gambar && !str_starts_with($produkPo->gambar, 'http') && Storage::disk('public')->exists($produkPo->gambar)) {
                Storage::disk('public')->delete($produkPo->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('produk_pos', 'public');
        } elseif ($request->filled('gambar_url')) {
            if ($produkPo->gambar && !str_starts_with($produkPo->gambar, 'http') && Storage::disk('public')->exists($produkPo->gambar)) {
                Storage::disk('public')->delete($produkPo->gambar);
            }
            $validated['gambar'] = $request->input('gambar_url');
        } else {
            unset($validated['gambar']);
        }

        $produkPo->update($validated);

        return redirect()->route('admin.produk-pos.index')
                         ->with('success', 'Produk POS berhasil diperbarui!');
    }

    public function destroy(ProdukPos $produkPo)
    {
        if ($produkPo->gambar && !str_starts_with($produkPo->gambar, 'http') && Storage::disk('public')->exists($produkPo->gambar)) {
            Storage::disk('public')->delete($produkPo->gambar);
        }

        $produkPo->delete();

        return redirect()->route('admin.produk-pos.index')
                         ->with('success', 'Produk POS berhasil dihapus!');
    }
}
