<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::query();

        if ($request->filled('search')) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $menus = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku'        => 'required|string|max:50|unique:menus,sku',
            'nama_menu'  => 'required|string|max:255',
            'harga'      => 'required|numeric|min:0',
            'kategori'   => 'required|in:Katering,Dine-in,Minuman,Snack,Dessert',
            'badge'      => 'nullable|string|max:100',
            'isi_paket'  => 'nullable|string',
            'deskripsi'  => 'nullable|string',
            'gambar_url' => 'nullable|url|max:2048',
            'gambar'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Proses isi_paket: ubah dari textarea baris-per-baris ke array JSON
        $validated['isi_paket'] = $this->parseIsiPaket($request->input('isi_paket'));

        // Upload gambar atau URL
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('menus', 'public');
        } elseif ($request->filled('gambar_url')) {
            $validated['gambar'] = $request->input('gambar_url');
        } else {
            unset($validated['gambar']);
        }

        Menu::create($validated);

        return redirect()->route('admin.menus.index')
                         ->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        return view('admin.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'sku'        => 'required|string|max:50|unique:menus,sku,' . $menu->id,
            'nama_menu'  => 'required|string|max:255',
            'harga'      => 'required|numeric|min:0',
            'kategori'   => 'required|in:Katering,Dine-in,Minuman,Snack,Dessert',
            'badge'      => 'nullable|string|max:100',
            'isi_paket'  => 'nullable|string',
            'deskripsi'  => 'nullable|string',
            'gambar_url' => 'nullable|url|max:2048',
            'gambar'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['isi_paket'] = $this->parseIsiPaket($request->input('isi_paket'));

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada dan bukan URL eksternal
            if ($menu->gambar && !str_starts_with($menu->gambar, 'http') && Storage::disk('public')->exists($menu->gambar)) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('menus', 'public');
        } elseif ($request->filled('gambar_url')) {
            if ($menu->gambar && !str_starts_with($menu->gambar, 'http') && Storage::disk('public')->exists($menu->gambar)) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $validated['gambar'] = $request->input('gambar_url');
        } else {
            unset($validated['gambar']);
        }

        $menu->update($validated);

        return redirect()->route('admin.menus.index')
                         ->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
            Storage::disk('public')->delete($menu->gambar);
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')
                         ->with('success', 'Menu berhasil dihapus!');
    }

    private function parseIsiPaket(?string $input): ?array
    {
        if (empty($input)) {
            return null;
        }

        $items = array_filter(
            array_map('trim', explode("\n", $input)),
            fn($item) => $item !== ''
        );

        return array_values($items);
    }
}
