<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Rka extends Model
{
    use HasFactory;


    protected $fillable = [
        'kode_rekening_id',
        'bulan',
        'kode_rekening',
        'uraian',
        'sub_uraian',
        'saldo_awal_mode',
        'saldo_awal_jumlah',
        'saldo_awal_satuan',
        'saldo_awal_harga',
        'saldo_awal_total',
        'pembelian_mode',
        'pembelian_jumlah',
        'pembelian_satuan',
        'pembelian_harga',
        'pembelian_total',
        'saldo_akhir_mode',
        'saldo_akhir_jumlah',
        'saldo_akhir_satuan',
        'saldo_akhir_harga',
        'saldo_akhir_total',
        'rusak_mode',
        'rusak_jumlah',
        'rusak_satuan',
        'rusak_harga',
        'rusak_total',
        'beban_mode',
        'beban_jumlah',
        'beban_satuan',
        'beban_harga',
        'beban_total',
    ];

    public function kodeRekening()
{
    return $this->belongsTo(KodeRekening::class);
}



    /**
     * Hitung total jika mode = detail. Dipanggil dari Controller sebelum create/update.
     */
    public static function withTotals(array $data): array
    {
        // Hitung total untuk section yang bisa detail (saldo_awal, pembelian, saldo_akhir, rusak)
        foreach (['saldo_awal', 'pembelian', 'saldo_akhir', 'rusak'] as $sec) {
            $mode = $data[$sec . '_mode'] ?? 'total';
            if ($mode === 'detail') {
                $j = (float) ($data[$sec . '_jumlah'] ?? 0);
                $h = (float) ($data[$sec . '_harga'] ?? 0);
                $data[$sec . '_total'] = round($j * $h, 2);
            } else {
                // pastikan total ada angka (bisa dari input total)
                $data[$sec . '_total'] = isset($data[$sec . '_total']) ? round((float)$data[$sec . '_total'], 2) : (float) ($data[$sec . '_total'] ?? 0);
            }
        }

        // 🔥 Kalkulasi Beban Persediaan menurut rumus baru:
        $sa = (float) ($data['saldo_awal_total'] ?? 0);
        $pb = (float) ($data['pembelian_total'] ?? 0);
        $sa_akhir = (float) ($data['saldo_akhir_total'] ?? 0);
        $rs = (float) ($data['rusak_total'] ?? 0);

        $data['beban_total'] = round($sa + $pb - $sa_akhir - $rs, 2);

        // Pastikan beban disimpan sebagai total (user nggak input manual)
        // Beban ikut mode dari input (biarin detail atau total)
        $data['beban_mode'] = $data['beban_mode'] ?? 'total';

        // Optional: kosongkan field detail beban supaya tidak tersimpan (atau biarkan null)
        $data['beban_jumlah'] = $data['beban_jumlah'] ?? null;
        $data['beban_satuan'] = $data['beban_satuan'] ?? null;
        $data['beban_harga']  = $data['beban_harga'] ?? null;

        return $data;
    }
}