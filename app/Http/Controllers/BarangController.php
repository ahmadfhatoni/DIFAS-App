<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'harga_sewa' => 'required|numeric|min:1',
            'stok' => 'required|integer|min:1',
        ],[ 
            'foto.required' => 'Foto Barang harus diisi.',
            'foto.image' => 'Foto Barang harus berupa gambar.',
            'foto.mimes' => 'Foto Barang harus berupa file dengan tipe: jpeg, png, jpg.',
            'id.required' => 'ID Barang tidak boleh kosong.',// Pesan error lainnya
            'id.unique' => 'ID Barang sudah terdaftar.',
        ]);

        if ($request->hasFile('foto')) {
            $filename = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('uploads'), $filename);
            $validated['foto'] = 'uploads/' . $filename;
        }

        Barang::create($validated);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function create()
    {
        return view('barang.create');
    }

    public function index(Request $request)
    {
        // Query untuk mengambil barang
        $query = Barang::query();

        // Filter pencarian berdasarkan nama barang jika ada input search
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Ambil data pesanan beserta detailnya
        $barang = $query->get(); // Ambil semua data dari tabel barang
        return view('barang.index', compact('barang'));
    }
       
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }
    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'harga_sewa' => 'required|numeric|min:1',
            'stok' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $barang = Barang::findOrFail($id);

        if ($request->hasFile('foto')) {
            $filename = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('uploads'), $filename);
            $validated['foto'] = 'uploads/' . $filename;
        }

        $barang->update($validated);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate!');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        
        if ($barang->foto && file_exists(public_path($barang->foto))) {
            unlink(public_path($barang->foto)); // Hapus file foto
        }

        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }

}
