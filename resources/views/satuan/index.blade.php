@extends('layouts.app')
@section('title', 'Satuan Barang')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Satuan Barang</h4>
        <a href="{{ route('satuans.create') }}" class="btn btn-primary">Tambah</a>
    </div>


    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Satuan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->nama }}</td>
                        <td class="text-end">
                            <a href="{{ route('satuans.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('satuans.destroy', $item) }}" method="POST" class="d-inline">
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
