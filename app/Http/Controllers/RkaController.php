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
        $rkas = $q->orderBy('bulan', 'desc')  // bulan terbaru dulu
            ->orderBy('id', 'desc')     // dalam bulan, data terbaru dulu
            ->paginate(10)
            ->withQueryString();
        return view('rka.index', compact('rkas'));
    }

    public function create()
    {
        $kodeRekenings = KodeRekening::orderBy('created_at', 'desc')->get();
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

        // simpan id, bukan kode
        $data['kode_rekening_id'] = $r->kode_rekening_id;

        // ambil uraian dari input kalau kosong, fallback ke tabel kode_rekenings
        if ($data['kode_rekening_id']) {
            $kode = KodeRekening::find($data['kode_rekening_id']);
            if ($kode) {
                $data['kode_rekening'] = $kode->kode;
                $data['uraian'] = $kode->uraian;
            }
        } else {
            $data['kode_rekening'] = '-';
            $data['uraian'] = $r->uraian;
        }

        // set bulan & tahun
        $now = now();
        $data['bulan'] = $r->bulan ?? $now->month;
        $data['tahun'] = $now->year;

        $data = Rka::withTotals($data);
        $data['kode_rekening_id'] = $r->kode_rekening_id;
        Rka::create($data);

        return redirect()->route('rka.index')->with('success', 'Data berhasil ditambahkan.');
    }


    public function edit(Rka $rka)
    {
        $kodeRekenings = KodeRekening::orderBy('created_at', 'desc')->get();
        $satuans = \App\Models\Satuan::orderBy('nama')->get();

        return view('rka.form', compact('rka', 'kodeRekenings', 'satuans'));
    }

    public function update(Request $r, Rka $rka)
    {
        $data = $this->validateData($r);

        // simpan id, bukan kode
        $data['kode_rekening_id'] = $r->kode_rekening_id;

        // ambil uraian dari input kalau kosong, fallback ke tabel kode_rekenings
        if ($data['kode_rekening_id']) {
            $kode = KodeRekening::find($data['kode_rekening_id']);
            if ($kode) {
                $data['kode_rekening'] = $kode->kode;
                $data['uraian'] = $kode->uraian;
            }
        } else {
            $data['kode_rekening'] = '-';
            $data['uraian'] = $r->uraian;
        }

        // set bulan & tahun
        $now = now();
        $data['bulan'] = $r->bulan ?? $now->month;
        $data['tahun'] = $now->year;

        $data = Rka::withTotals($data);
        $data['kode_rekening_id'] = $r->kode_rekening_id;
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
            'kode_rekening_id' => ['nullable', 'exists:kode_rekenings,id'],
            'kode_rekening' => ['nullable', 'string', 'max:50'],
            'uraian' => [
                function ($attribute, $value, $fail) use ($r) {
                    if (empty($r->kode_rekening_id) && empty($value)) {
                        $fail('Uraian harus diisi jika tidak memilih kode rekening.');
                    }
                }
            ],
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
        if ($bulan) $q->where('bulan', $bulan);
        if ($tahun) $q->where('tahun', $tahun);


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

        // Gunakan groupData supaya semua perhitungan konsisten
        $grouped = $this->groupData($items);

        $periode = $request->get('periode') ?: now()->format('Y-m');
        $periodeText = \Carbon\Carbon::createFromFormat('Y-m', $periode)->translatedFormat('F Y');

        return \Barryvdh\DomPDF\Facade\Pdf::loadView('rka.pdf', [
            'grouped' => $grouped,
            'periodeText' => ucfirst($periodeText),
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
        $q->where('tahun', $tahun)
            ->where('bulan', $bulan);

        $items = $q->orderBy('id', 'asc')->get();


        // Sama kayak exportPdf
        $grouped = $this->groupData($items);

        $periodeText = ucfirst(
            \Carbon\Carbon::createFromDate($tahun, $bulan, 1)
                ->locale('id') // paksa bahasa Indonesia
                ->translatedFormat('F Y')
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
        $q->where('tahun', $tahun);

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

            // Gunakan kombinasi kode_rekening + uraian sebagai key
            $key = $item->kode_rekening_id . '|' . $uraian;

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'uraian' => $uraian,
                    'kode_rekening' => $item->kode_rekening,
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

            $grouped[$key]['items'][] = $item;

            if (!empty($item->sub_uraian)) {
                $grouped[$key]['hasSub'] = true;
            }

            foreach (['saldo_awal', 'pembelian', 'saldo_akhir', 'rusak', 'beban'] as $sec) {
                $grouped[$key]['totals'][$sec . '_total'] += $item->{$sec . '_total'} ?? 0;
                $grouped[$key]['totals'][$sec . '_jumlah'] += $item->{$sec . '_jumlah'} ?? 0;
            }

            $lastUraian = $uraian;
        }


        return $grouped;
    }

    /**
     * 🔹 Export Excel
     */
}