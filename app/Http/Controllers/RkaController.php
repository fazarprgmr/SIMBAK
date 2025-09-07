<?php

namespace App\Http\Controllers;

use App\Models\Rka;
use App\Models\KodeRekening;
use Illuminate\Http\Request;
use App\Exports\RkaExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RkaController extends Controller
{
    public function index(Request $request)
    {
        $q = Rka::query();
        if ($s = $request->get('search')) {
            $q->where(fn($x) => $x->where('kode_rekening', 'like', "%$s%")
                ->orWhere('uraian', 'like', "%$s%")
                ->orWhere('sub_uraian', 'like', "%$s%"));
        }
        $rkas = $q->orderBy('id', 'asc')->paginate(10)->withQueryString();
        return view('rka.index', compact('rkas'));
    }

    public function create()
    {
        $kodeRekenings = KodeRekening::orderBy('kode')->get();
        $satuans = \App\Models\Satuan::orderBy('nama')->get();

        return view('rka.form', [
            'rka' => new Rka(),
            'kodeRekenings' => $kodeRekenings,
            'satuans' => $satuans
        ]);
    }

    public function store(Request $r)
    {
        $data = $this->validateData($r);

        if (!empty($data['kode_rekening'])) {
            $kode = \App\Models\KodeRekening::where('kode', $data['kode_rekening'])->first();
            if ($kode) {
                $data['uraian'] = $kode->uraian;
            }
        }


        // Otomatis isi bulan & tahun
        $now = Carbon::now();
        $data['bulan'] = $now->month;
        $data['tahun'] = $now->year;

        $data = Rka::withTotals($data);
        Rka::create($data);

        return redirect()->route('rka.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Rka $rka)
    {
        $kodeRekenings = KodeRekening::orderBy('kode')->get();
        $satuans = \App\Models\Satuan::orderBy('nama')->get();

        return view('rka.form', compact('rka', 'kodeRekenings', 'satuans'));
    }

    public function update(Request $r, Rka $rka)
    {
        $data = $this->validateData($r);

        if (!empty($data['kode_rekening'])) {
            $kode = \App\Models\KodeRekening::where('kode', $data['kode_rekening'])->first();
            if ($kode) {
                $data['uraian'] = $kode->uraian;
            }
        }

        // Kalau mau update, bulan/tahun tetap sesuai tanggal update:
        $now = Carbon::now();
        $data['bulan'] = $now->month;
        $data['tahun'] = $now->year;

        $data = Rka::withTotals($data);
        $rka->update($data);

        return redirect()->route('rka.index')->with('success-update', 'Data berhasil diperbarui.');
    }

    public function destroy(Rka $rka)
    {
        $rka->delete();
        return back()->with('ok', 'Data Berhasil Dihapus.');
    }

    private function validateData(Request $r): array
    {
        $rules = [
            'kode_rekening' => ['nullable', 'string', 'max:50'],
            'uraian' => ['nullable', 'string', 'max:255'],
            'sub_uraian' => ['nullable', 'string', 'max:255'],
        ];


        foreach (['saldo_awal', 'pembelian', 'saldo_akhir', 'rusak', 'beban'] as $sec) {
            $rules[$sec . '_mode'] = ['required', 'in:total,detail'];
            $rules[$sec . '_jumlah'] = ['nullable', 'numeric'];
            $rules[$sec . '_satuan'] = ['nullable', 'string', 'max:50'];
            $rules[$sec . '_harga'] = ['nullable', 'numeric'];
            $rules[$sec . '_total'] = ['nullable', 'numeric'];
        }

        return $r->validate($rules);
    }

    /**
     * 🔹 Halaman Laporan (Filter Bulan/Tahun)
     */
    public function laporan(Request $request)
    {
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');

        $q = Rka::query();
        if ($bulan) $q->whereMonth('created_at', $bulan);
        if ($tahun) $q->whereYear('created_at', $tahun);

        $items = $q->orderBy('id', 'asc')->get();

        return view('laporan.index', compact('items', 'bulan', 'tahun'));
    }

    /**
     * 🔹 Export PDF
     */
    public function exportPdf(Request $request)
    {
        $q = Rka::query();

        if ($s = $request->get('search')) {
            $q->where(fn($x) => $x->where('kode_rekening', 'like', "%$s%")
                ->orWhere('uraian', 'like', "%$s%")
                ->orWhere('sub_uraian', 'like', "%$s%"));
        }
        if ($kode = $request->get('kode')) {
            $q->where('kode_rekening', $kode);
        }

        $items = $q->orderBy('id', 'asc')->get();

        // 🔥 Gunakan groupData supaya semua perhitungan konsisten
        $grouped = $this->groupData($items);

        $periode = $request->get('periode') ?: now()->format('Y-m');
        $periodeText = \Carbon\Carbon::createFromFormat('Y-m', $periode)->translatedFormat('F Y');

        return \Barryvdh\DomPDF\Facade\Pdf::loadView('rka.pdf', [
            'grouped' => $grouped,
            'periodeText' => mb_strtoupper($periodeText),
        ])
            ->setPaper('folio', 'landscape')
            ->set_option('dpi', 96)
            ->download('Rincian-Barang-Persediaan-SKPD-Kecamatan-Ciasem.pdf');
    }




    public function exportPdfBulanan(Request $request)
    {
        $bulan = $request->get('bulan') ?? now()->month;
        $tahun = $request->get('tahun') ?? now()->year;

        $q = Rka::query();
        $q->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan);

        $items = $q->orderBy('id', 'asc')->get();


        // 🔥 Sama kayak exportPdf
        $grouped = $this->groupData($items);

        $periodeText = strtoupper(
            \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y')
        );

        return \Barryvdh\DomPDF\Facade\Pdf::loadView('rka.pdf', [
            'grouped' => $grouped,
            'periodeText' => $periodeText,
        ])
            ->setPaper('folio', 'landscape')
            ->set_option('dpi', 96)
            ->download("Rincian-Barang-Persediaan-SKPD-Kecamatan-Ciasem-{$bulan}-{$tahun}.pdf");
    }



    public function exportPdfTahunan(Request $request)
    {
        $tahun = $request->get('tahun') ?? now()->year;

        $q = Rka::query();
        $q->whereYear('created_at', $tahun);

        $items = $q->orderBy('id', 'asc')->get();

        $grouped = $this->groupData($items);

        $periodeText = "TAHUN {$tahun}";

        return \Barryvdh\DomPDF\Facade\Pdf::loadView('rka.pdf', [
            'grouped' => $grouped,
            'periodeText' => $periodeText,
        ])
            ->setPaper('folio', 'landscape')
            ->set_option('dpi', 96)
            ->download("Rincian-Barang-Persediaan-SKPD-Kecamatan-Ciasem-{$tahun}.pdf");
    }

    private function groupData($items)
    {
        $grouped = [];
        $lastUraian = null;

        // helper normalisasi jumlah/harga/total
        $normalize = function ($item, $prefix) {
            $j = $item->{$prefix . '_jumlah'} ?? null;
            $h = $item->{$prefix . '_harga'} ?? null;
            $t = $item->{$prefix . '_total'} ?? null;
            $s = $item->{$prefix . '_satuan'} ?? null;

            if (($j === null || $j === '') && $t !== null && $h && $h != 0) $j = $t / $h;
            if (($h === null || $h === '') && $t !== null && $j && $j != 0) $h = $t / $j;
            if (($t === null || $t === '') && $j !== null && $h !== null) $t = $j * $h;

            $item->{$prefix . '_jumlah'} = is_numeric($j) ? (float)$j : 0;
            $item->{$prefix . '_harga'}  = is_numeric($h) ? (float)$h : 0;
            $item->{$prefix . '_total'}  = is_numeric($t) ? (float)$t : 0;
            $item->{$prefix . '_satuan'} = $s ?? '';
        };

        foreach ($items as $item) {
            $uraian = $item->uraian ?: $lastUraian;

            if (!isset($grouped[$uraian])) {
                $grouped[$uraian] = [
                    'items' => [],
                    'totals' => [
                        'saldo_awal_total' => 0,
                        'pembelian_total' => 0,
                        'saldo_akhir_total' => 0,
                        'rusak_total' => 0,
                        'beban_total' => 0,
                        'saldo_awal_jumlah' => 0,
                        'pembelian_jumlah' => 0,
                        'saldo_akhir_jumlah' => 0,
                        'rusak_jumlah' => 0,
                        'beban_jumlah' => 0,
                    ],
                    'hasSub' => false,
                ];
            }

            // Normalisasi semua bagian
            foreach (['saldo_awal', 'pembelian', 'saldo_akhir', 'rusak'] as $sec) {
                $normalize($item, $sec);
            }

            // 🔥 Mode Beban
            if ($item->beban_mode === 'total') {
                // Ambil dari database langsung
                $item->beban_total  = (float)($item->beban_total ?? 0);
                $item->beban_jumlah = (float)($item->beban_jumlah ?? 0);
                $item->beban_harga  = (float)($item->beban_harga ?? 0);
                $item->beban_satuan = $item->beban_satuan ?? '';
            } else {
                // Hitung kalau mode detail
                $beban_jumlah = ($item->saldo_awal_jumlah ?? 0)
                    + ($item->pembelian_jumlah ?? 0)
                    - ($item->saldo_akhir_jumlah ?? 0)
                    - ($item->rusak_jumlah ?? 0);

                $beban_harga = $item->saldo_awal_harga
                    ?: $item->pembelian_harga
                    ?: $item->saldo_akhir_harga
                    ?: $item->rusak_harga
                    ?: 0;

                $item->beban_jumlah = $beban_jumlah;
                $item->beban_harga  = $beban_harga;
                $item->beban_total  = $beban_jumlah * $beban_harga;
                $item->beban_satuan = $item->saldo_awal_satuan
                    ?: $item->pembelian_satuan
                    ?: $item->saldo_akhir_satuan
                    ?: $item->rusak_satuan
                    ?: '';
            }

            // Tambah ke grouped
            $grouped[$uraian]['items'][] = $item;

            if (!empty($item->sub_uraian)) {
                $grouped[$uraian]['hasSub'] = true;
            }

            // Total akumulasi
            foreach (['saldo_awal', 'pembelian', 'saldo_akhir', 'rusak', 'beban'] as $sec) {
                $grouped[$uraian]['totals'][$sec . '_total'] += $item->{$sec . '_total'} ?? 0;
                $grouped[$uraian]['totals'][$sec . '_jumlah'] += $item->{$sec . '_jumlah'} ?? 0;
            }

            $lastUraian = $uraian;
        }

        return $grouped;
    }

    /**
     * 🔹 Export Excel
     */
}
