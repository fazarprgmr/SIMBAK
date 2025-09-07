<?php

namespace App\Http\Controllers;

use App\Models\Rka;
use App\Models\KodeRekening;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total data
        $totalRka = Rka::count();
        $totalKode = KodeRekening::count();
        $totalSatuan = Satuan::count();
        $laporanBulanIni = Rka::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Grafik pembelian per bulan
        $pembelian = Rka::selectRaw('MONTH(created_at) as bulan, SUM(pembelian_total) as total')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->pluck('total', 'bulan');

        $bulan = [];
        $totalPembelian = [];
        foreach (range(1, 12) as $i) {
            $bulan[] = date('M', mktime(0, 0, 0, $i, 1));
            $totalPembelian[] = $pembelian[$i] ?? 0;
        }

        // Barang terbaru
        $barangTerbaru = Rka::orderBy('created_at', 'desc')->take(5)->get();

        // Barang rusak/stock rendah
        $barangRusak = Rka::where('rusak_jumlah', '>', 0)->take(5)->get();

        return view('dashboard', compact(
            'totalRka',
            'totalKode',
            'totalSatuan',
            'laporanBulanIni',
            'bulan',
            'totalPembelian',
            'barangTerbaru',
            'barangRusak'
        ));
    }
}
