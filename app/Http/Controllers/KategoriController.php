<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

/**
 * Kategori Controller
 * 
 * Handles category management functionality
 */
class KategoriController extends Controller
{
    /**
     * Display a listing of categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        
        $kategori = Kategori::all();
        return view('kategori.index', compact('kategori'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        
        return view('kategori.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kategori,nama|regex:/^[a-zA-Z\s]+$/',
        ], [
            'nama.regex' => 'Nama kategori hanya boleh berisi huruf dan spasi.',
            'nama.required' => 'Nama kategori harus diisi.',
            'nama.max' => 'Nama kategori maksimal 100 karakter.',
            'nama.unique' => 'Nama kategori sudah ada.',
        ]);

        Kategori::create($validated);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kategori,nama,' . $id . '|regex:/^[a-zA-Z\s]+$/',
        ], [
            'nama.regex' => 'Nama kategori hanya boleh berisi huruf dan spasi.',
            'nama.required' => 'Nama kategori harus diisi.',
            'nama.max' => 'Nama kategori maksimal 100 karakter.',
            'nama.unique' => 'Nama kategori sudah ada.',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($validated);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil diupdate!');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if (auth()->check() && auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Only owners can access this page.');
        }
        
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
