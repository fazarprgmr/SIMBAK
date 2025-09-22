@extends('layouts.app')
@section('title', $item->exists ? 'Edit Kode Rekening' : 'Tambah Kode Rekening')

@section('content')

    {{-- Petunjuk Penggunaan --}}
    <div class="alert alert-info">
        <strong>ℹ️ Petunjuk Penggunaan:</strong>
        <ul class="mb-0">
            <li><strong>Kode Rekening</strong> isi Kode Rekening Menggunakan angka.</li>
            <li> <strong>Kode Rekening</strong> gunakan - jika Uraian tidak memiliki Kode Rekening</li>
            <li><strong>Uraian</strong> Wajib di isi bisa dengan kombinasi huruf dan angka</li>
        </ul>
    </div>

    <h4>{{ $item->exists ? 'Edit' : 'Tambah' }} Kode Rekening</h4>

    <form action="{{ $item->exists ? route('kode-rekenings.update', $item) : route('kode-rekenings.store') }}" method="POST">
        @csrf
        @if ($item->exists)
            @method('PUT')
        @endif

        <div class="mb-3">
            <label>Kode</label>
            <input type="text" name="kode" class="form-control" value="{{ old('kode', $item->kode) }}" required>
            @error('kode')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Uraian</label>
            <input type="text" name="uraian" class="form-control" value="{{ old('uraian', $item->uraian) }}" required>
            @error('uraian')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('kode-rekenings.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
