@extends('layouts.app')
@section('title', 'Kode Rekening')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Kode Rekening</h4>
        <a href="{{ route('kode-rekenings.create') }}" class="btn btn-primary">+ Tambah Data</a>
    </div>

    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Uraian</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->uraian }}</td>
                        <td class="text-end">
                            <a href="{{ route('kode-rekenings.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('kode-rekenings.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $items->links('pagination::bootstrap-5') }}
    </div>

@endsection
