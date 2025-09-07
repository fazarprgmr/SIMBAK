@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">📊 Laporan Rincian Barang Persediaan</h5>
        </div>
        <div class="card-body">
            <form action="" method="GET" id="laporanForm">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Pilih Bulan</label>
                        <select name="bulan" class="form-select">
                            <option value="">Semua Bulan</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Pilih Tahun</label>
                        <select name="tahun" class="form-select">
                            <option value="">Semua Tahun</option>
                            @for ($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="d-flex" style="gap: 17%;">
                    <button type="submit" formaction="{{ route('rka.export.bulanan') }}" class="btn btn-success">
                        Export PDF Bulanan
                    </button>
                    <button type="submit" formaction="{{ route('rka.export.tahunan') }}" class="btn btn-danger">
                        Export PDF Tahunan
                    </button>
                </div>


            </form>
        </div>
    </div>
@endsection
