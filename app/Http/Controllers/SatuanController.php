<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $items = Satuan::orderBy('nama')->paginate(10);
        return view('satuan.index', compact('items'));
    }

    public function create()
    {
        return view('satuan.form', ['item' => new Satuan()]);
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|unique:satuans']);
        Satuan::create($request->only('nama'));
        return redirect()->route('satuans.index')->with('success', 'Satuan berhasil ditambahkan');
    }

    public function edit(Satuan $satuan)
    {
        return view('satuan.form', ['item' => $satuan]);
    }

    public function update(Request $request, Satuan $satuan)
    {
        $request->validate(['nama' => 'required|unique:satuans,nama,' . $satuan->id]);
        $satuan->update($request->only('nama'));
        return redirect()->route('satuans.index')->with('success-update', 'Satuan berhasil diupdate');
    }

    public function destroy(Satuan $satuan)
    {
        $satuan->delete();
        return redirect()->route('satuans.index')->with('ok', 'Satuan berhasil dihapus');
    }
}
