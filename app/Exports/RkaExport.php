<?php

namespace App\Exports;

use App\Models\Rka;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;

class RkaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    protected $bulan;
    protected $tahun;

    // 🔹 Bisa terima filter dari controller
    public function __construct($bulan = null, $tahun = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $q = Rka::query();
        if ($this->bulan) {
            $q->whereMonth('created_at', $this->bulan);
        }
        if ($this->tahun) {
            $q->whereYear('created_at', $this->tahun);
        }
        return $q->orderBy('kode_rekening')->get();
    }

    public function startCell(): string
    {
        return 'A3'; // Mulai dari baris ke-3
    }

    public function headings(): array
    {
        return [
            [
                'Kode Rekening',
                'Uraian',
                'Sub Uraian',
                'Saldo Awal', '', '', '',
                'Pembelian', '', '', '',
                'Saldo Akhir', '', '', '',
                'Rusak', '', '', '',
                'Beban', '', '', ''
            ],
            [
                '', '', '',
                'Jumlah', 'Satuan', 'Harga', 'Total',
                'Jumlah', 'Satuan', 'Harga', 'Total',
                'Jumlah', 'Satuan', 'Harga', 'Total',
                'Jumlah', 'Satuan', 'Harga', 'Total',
                'Jumlah', 'Satuan', 'Harga', 'Total'
            ]
        ];
    }

    public function map($rka): array
    {
        $fmt = fn($v, $num = false) => ($v === null || $v == 0)
            ? ''
            : ($num ? number_format($v, 0, ',', '.') : $v);

        return [
            $rka->kode_rekening ?: '',
            $rka->uraian ?: '',
            $rka->sub_uraian ?: '',

            $fmt($rka->saldo_awal_jumlah),
            $fmt($rka->saldo_awal_satuan),
            $fmt($rka->saldo_awal_harga, true),
            $fmt($rka->saldo_awal_total, true),

            $fmt($rka->pembelian_jumlah),
            $fmt($rka->pembelian_satuan),
            $fmt($rka->pembelian_harga, true),
            $fmt($rka->pembelian_total, true),

            $fmt($rka->saldo_akhir_jumlah),
            $fmt($rka->saldo_akhir_satuan),
            $fmt($rka->saldo_akhir_harga, true),
            $fmt($rka->saldo_akhir_total, true),

            $fmt($rka->rusak_jumlah),
            $fmt($rka->rusak_satuan),
            $fmt($rka->rusak_harga, true),
            $fmt($rka->rusak_total, true),

            $fmt($rka->beban_jumlah),
            $fmt($rka->beban_satuan),
            $fmt($rka->beban_harga, true),
            $fmt($rka->beban_total, true),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merge header
                $sheet->mergeCells('D1:G1');
                $sheet->mergeCells('H1:K1');
                $sheet->mergeCells('L1:O1');
                $sheet->mergeCells('P1:S1');
                $sheet->mergeCells('T1:W1');
                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');

                // Style header
                $sheet->getStyle('A1:W2')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFEEEEEE'],
                    ],
                ]);

                // Border semua sel
                $dataCount = $this->collection()->count();
                $lastRow = $dataCount + 2;
                $sheet->getStyle("A1:W{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                    'alignment' => ['wrapText' => true],
                ]);

                // Lebar kolom
                foreach (range('A', 'W') as $col) {
                    $sheet->getColumnDimension($col)->setWidth(15);
                }
            },
        ];
    }
}
