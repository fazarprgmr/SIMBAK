<?php

namespace App\Http\Controllers;

use App\Models\KodeRekening;
use Illuminate\Http\Request;

class KodeRekeningController extends Controller
{
    public function index()
    {
        $items = KodeRekening::orderBy('kode')->paginate(10);
        return view('kode_rekening.index', compact('items'));
        
    }

    public function create()
    {
        return view('kode_rekening.form', ['item' => new KodeRekening()]);
    }

    public function store(Request $request)
{
    $request->validate([
        'kode' => [
            'required',
            function ($attribute, $value, $fail) {
                if ($value !== '-' && \App\Models\KodeRekening::where('kode', $value)->exists()) {
                    $fail('Kode sudah ada.');
                }
            },
        ],
        'uraian' => 'required'
    ]);

    KodeRekening::create($request->only('kode', 'uraian'));
    return redirect()->route('kode-rekenings.index')->with('success', 'Kode Rekening berhasil ditambahkan');
}


    public function edit(KodeRekening $kodeRekening)
    {
        return view('kode_rekening.form', ['item' => $kodeRekening]);
    }

    public function update(Request $request, KodeRekening $kodeRekening)
{
    $request->validate([
        'kode' => [
            'required',
            function ($attribute, $value, $fail) use ($kodeRekening) {
                if ($value !== '-' && \App\Models\KodeRekening::where('kode', $value)
                        ->where('id', '!=', $kodeRekening->id)
                        ->exists()) {
                    $fail('Kode sudah ada.');
                }
            },
        ],
        'uraian' => 'required'
    ]);

    $kodeRekening->update($request->only('kode', 'uraian'));
    return redirect()->route('kode-rekenings.index')->with('success-update', 'Kode Rekening berhasil diupdate');
}

    public function destroy(KodeRekening $kodeRekening)
    {
        $kodeRekening->delete();
        return redirect()->route('kode-rekenings.index')->with('success', 'Kode Rekening berhasil dihapus');
    }
}
