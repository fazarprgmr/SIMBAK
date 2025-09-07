@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">📊 Dashboard</h2>

        <style>
            .shortcut-card {
                transition: all 0.3s ease-in-out;
                border-radius: 12px;
            }

            .shortcut-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            }

            .icon {
                font-size: 2.5rem;
            }
        </style>

        <!-- Shortcut Menu -->
        <div class="row mb-4">
            <div class="col-md-3">
                <a href="{{ route('rka.create') }}" class="text-decoration-none">
                    <div class="card shadow-sm text-center shortcut-card">
                        <div class="card-body">
                            <div class="icon mb-2 text-primary">
                                <i class="fa fa-plus-circle fa-3x"></i>
                            </div>
                            <h5 class="card-title">Tambah RKA</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('rka.laporan') }}" class="text-decoration-none">
                    <div class="card shadow-sm text-center shortcut-card">
                        <div class="card-body">
                            <div class="icon mb-2 text-success">
                                <i class="fa fa-file-pdf-o fa-3x"></i>
                            </div>
                            <h5 class="card-title">Cetak Laporan</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('kode-rekenings.create') }}" class="text-decoration-none">
                    <div class="card shadow-sm text-center shortcut-card">
                        <div class="card-body">
                            <div class="icon mb-2 text-info">
                                <i class="fa fa-book fa-3x"></i>
                            </div>
                            <h5 class="card-title">Tambah Kode Rekening</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('satuans.create') }}" class="text-decoration-none">
                    <div class="card shadow-sm text-center shortcut-card">
                        <div class="card-body">
                            <div class="icon mb-2 text-warning">
                                <i class="fa fa-cube fa-3x"></i>
                            </div>
                            <h5 class="card-title">Tambah Satuan</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>



        <div class="row">
            <!-- Card Statistik -->
            <div class="col-md-3">
                <a href="{{ route('rka.index') }}" class="text-decoration-none">
                <div class="card text-white bg-primary mb-3 shadow-sm shortcut-card">
                    <div class="card-body">
                        <h6>Total Pemasukan</h6>
                        <h3>{{ $totalRka }}</h3>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('kode-rekenings.index') }}" class="text-decoration-none">
                <div class="card text-white bg-success mb-3 shadow-sm shortcut-card">
                    <div class="card-body">
                        <h6>Kode Rekening</h6>
                        <h3>{{ $totalKode }}</h3>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('satuans.index') }}" class="text-decoration-none">
                <div class="card text-white bg-info mb-3 shadow-sm shortcut-card">
                    <div class="card-body">
                        <h6>Satuan Barang</h6>
                        <h3>{{ $totalSatuan }}</h3>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('rka.laporan') }}" class="text-decoration-none">
                <div class="card text-white bg-warning mb-3 shadow-sm shortcut-card">
                    <div class="card-body">
                        <h6>Laporan Bulan Ini</h6>
                        <h3>{{ $laporanBulanIni }}</h3>
                    </div>
                </div>
                </a>
            </div>
        </div>

        <!-- Grafik -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">📈 Grafik Total Pembelian per Bulan</div>
            <div class="card-body">
                <canvas id="pembelianChart" height="100"></canvas>
            </div>
        </div>

        <div class="row">
            <!-- Barang Terbaru -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">🆕 Barang Terbaru</div>
                    <div class="card-body table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Sub Uraian</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangTerbaru as $item)
                                    <tr>
                                        <td>{{ $item->kode_rekening }}</td>
                                        <td>{{ $item->sub_uraian }}</td>
                                        <td>{{ $item->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Barang Rusak -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">⚠️ Barang Rusak / Stock Tipis</div>
                    <div class="card-body table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Sub Uraian</th>
                                    <th>Rusak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangRusak as $item)
                                    <tr>
                                        <td>{{ $item->kode_rekening }}</td>
                                        <td>{{ $item->sub_uraian }}</td>
                                        <td>{{ $item->rusak_jumlah }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('pembelianChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($bulan),
                datasets: [{
                    label: 'Total Pembelian',
                    data: @json($totalPembelian),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
