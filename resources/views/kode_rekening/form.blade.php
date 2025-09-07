@extends('layouts.app')
@section('title', $item->exists ? 'Edit Kode Rekening' : 'Tambah Kode Rekening')

@section('content')
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
