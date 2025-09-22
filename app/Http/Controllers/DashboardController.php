<?php

namespace App\Http\Controllers;

use App\Models\Rka;
use App\Models\KodeRekening;
use App\Models\Satuan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Data dropdown filter
        $namaBulan = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        // Ambil bulan & tahun dari request (default bulan & tahun sekarang)
        $bulanDipilih = $request->get('bulan', now()->month);
        $tahunDipilih = $request->get('tahun', now()->year);

        // Total data
        $totalRka = Rka::count();
        $totalKode = KodeRekening::count();
        $totalSatuan = Satuan::count();

        // Total laporan bulan yang dipilih (pakai field bulan & tahun, bukan created_at)
        $laporanBulanIni = Rka::where('bulan', $bulanDipilih)
            ->where('tahun', $tahunDipilih)
            ->count();

        // Grafik pembelian per bulan (pakai field bulan & tahun inputan)
        $pembelian = Rka::selectRaw('bulan, SUM(pembelian_total) as total')
            ->where('tahun', $tahunDipilih)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $bulan = [];
        $totalPembelian = [];
        foreach (range(1, 12) as $i) {
            $bulan[] = $namaBulan[$i];
            $totalPembelian[] = $pembelian[$i] ?? 0;
        }

        // Barang terbaru (masih bisa pakai created_at untuk urutan)
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
            'barangRusak',
            'bulanDipilih',
            'tahunDipilih',
            'namaBulan'
        ));
    }
}