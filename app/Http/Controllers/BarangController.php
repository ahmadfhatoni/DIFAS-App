<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

/**
 * Barang Controller
 * 
 * Handles item management functionality
 */
class BarangController extends Controller
{
    /**
     * Display a listing of items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Barang::with('kategori');

        // Filter search by item name if search input exists
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $barang = $query->get();
        return view('barang.index', compact('barang'));
    }

    /**
     * Show the form for creating a new item.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $kategoriList = \App\Models\Kategori::all();
        return view('barang.create', compact('kategoriList'));
    }

    /**
     * Store a newly created item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'kategori_id' => 'required|exists:kategori,id',
            'harga_sewa' => 'required|numeric|min:1',
            'stok' => 'required|integer|min:1',
        ], [
            'foto.required' => 'Foto Barang harus diisi.',
            'foto.image' => 'Foto Barang harus berupa gambar.',
            'foto.mimes' => 'Foto Barang harus berupa file dengan tipe: jpeg, png, jpg.',
            'id.required' => 'ID Barang tidak boleh kosong.',
            'id.unique' => 'ID Barang sudah terdaftar.',
            'nama.regex' => 'Nama barang hanya boleh berisi huruf dan spasi.',
            'nama.required' => 'Nama barang harus diisi.',
            'nama.max' => 'Nama barang maksimal 255 karakter.',
            'kategori_id.required' => 'Kategori barang harus dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'harga_sewa.required' => 'Harga sewa harus diisi.',
            'harga_sewa.numeric' => 'Harga sewa harus berupa angka.',
            'harga_sewa.min' => 'Harga sewa minimal Rp 1.',
            'stok.required' => 'Stok harus diisi.',
            'stok.integer' => 'Stok harus berupa angka bulat.',
            'stok.min' => 'Stok minimal 1.',
        ]);

        if ($request->hasFile('foto')) {
            $filename = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('uploads'), $filename);
            $validated['foto'] = 'uploads/' . $filename;
        }

        Barang::create($validated);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified item.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $barang = Barang::with('kategori')->findOrFail($id);
        $kategoriList = \App\Models\Kategori::all();
        return view('barang.edit', compact('barang', 'kategoriList'));
    }

    /**
     * Update the specified item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'kategori_id' => 'required|exists:kategori,id',
            'harga_sewa' => 'required|numeric|min:1',
            'stok' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama.regex' => 'Nama barang hanya boleh berisi huruf dan spasi.',
            'nama.required' => 'Nama barang harus diisi.',
            'nama.max' => 'Nama barang maksimal 255 karakter.',
            'kategori_id.required' => 'Kategori barang harus dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'harga_sewa.required' => 'Harga sewa harus diisi.',
            'harga_sewa.numeric' => 'Harga sewa harus berupa angka.',
            'harga_sewa.min' => 'Harga sewa minimal Rp 1.',
            'stok.required' => 'Stok harus diisi.',
            'stok.integer' => 'Stok harus berupa angka bulat.',
            'stok.min' => 'Stok minimal 1.',
        ]);

        $barang = Barang::findOrFail($id);

        if ($request->hasFile('foto')) {
            $filename = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('uploads'), $filename);
            $validated['foto'] = 'uploads/' . $filename;
        }

        $barang->update($validated);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diupdate!');
    }

    /**
     * Remove the specified item from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        
        // Delete photo file if exists
        if ($barang->foto && file_exists(public_path($barang->foto))) {
            unlink(public_path($barang->foto));
        }

        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
}
