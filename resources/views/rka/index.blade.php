@extends('layouts.app')
@section('title', 'Data Pemasukan Barang')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>📄 Data Pemasukan Barang</h4>
        <div> <a href="{{ route('rka.create') }}" class="btn btn-primary btn-sm">+ Tambah Data</a></div>
    </div>

    <form class="row g-2 mb-3">
        <div class="col-auto"> <input class="form-control" name="search" placeholder="Cari kode/uraian"
                value="{{ request('search') }}"> </div>
        <div class="col-auto"> <button class="btn btn-outline-secondary">Cari</button> </div>
    </form>
    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table table-striped align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode Rekening</th>
                    <th>Uraian</th>
                    <th>Sub Uraian</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rkas as $rka)
                    <tr>
                        <td>{{ $rka->kode_rekening }}</td>
                        <td>{{ $rka->uraian }}</td>
                        <td>{{ $rka->sub_uraian }}</td>
                        <td class="text-end"> <a href="{{ route('rka.edit', $rka) }}"
                                class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('rka.destroy', $rka) }}" method="POST" class="d-inline delete-form">
                                @csrf 
                                @method('DELETE') 
                                <button type="button" class="btn btn-danger btn-sm delete-btn">Hapus</button> </form>
                        </td>
                </tr> @empty <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3 d-flex justify-content-center">
        {{ $rkas->links('pagination::bootstrap-5') }}
    </div>

@endsection
