<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        $kategori = Kategori::all();
        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kategori,nama',
        ]);

        Kategori::create($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kategori,nama,' . $id,
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy($id)
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
