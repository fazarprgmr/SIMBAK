@extends('layouts.app')
@section('title', $item->exists ? 'Edit Satuan Barang' : 'Tambah Satuan Barang')

@section('content')

{{-- Petunjuk Penggunaan --}}
    <div class="alert alert-info">
        <strong>ℹ️ Petunjuk Penggunaan:</strong>
        <ul class="mb-0">
            <li><strong>Satuan Barang</strong> Wajib di isi bisa dengan kombinasi huruf dan angka</li>
            <li><strong>Contoh Satuan Barang:</strong> PAK, LUSIN, RIM, DLL</li>
        </ul>
    </div>

    <h4>{{ $item->exists ? 'Edit' : 'Tambah' }} Satuan Barang</h4>

    <form action="{{ $item->exists ? route('satuans.update', $item) : route('satuans.store') }}" method="POST">
        @csrf
        @if ($item->exists)
            @method('PUT')
        @endif

        <div class="mb-3">
            <label>Satuan Barang</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $item->nama) }}" required>
            @error('nama')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('satuans.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
