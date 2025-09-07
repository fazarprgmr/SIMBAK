<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan RKA</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px;
            font-size: 10px;
        }

        th {
            background: #eee;
            text-align: center;
        }

        td {
            text-align: center;
        }

        .left {
            text-align: left;
        }

        /* Biar cell total nyatu, cuma ada border luar */
        .merge-cell {
            border: 1px solid #000 !important;
            border-left: 1px solid #000 !important;
            border-right: 1px solid #000 !important;
            background: none !important;
        }
    </style>
</head>

<body>
    <h3 style="text-align:center;">RINCIAN BARANG PERSEDIAAN SKPD KECAMATAN CIASEM<br>{{ $periodeText }}</h3>

    @php
        function fmt($val, $num = false, $isJumlah = false)
        {
            if ($val === null || $val === '') {
                return '';
            }

            // Kalau jumlah (jumlah barang) nggak perlu .00
            if ($isJumlah) {
                if ($val == 0) {
                    return '-';
                }
                return intval($val) == $val ? number_format($val, 0, ',', '.') : number_format($val, 2, ',', '.');
            }

            // Kalau angka biasa
            if ($val == 0) {
                return '-';
            }

            return number_format($val, 0, ',', '.');
        }
    @endphp

    @php
        function fmtRusak($val)
        {
            if ($val === null || $val === '') {
                return '';
            }

            if ($val == 0) {
                return '-';
            }

            return number_format($val, 0, ',', '.');
        }
    @endphp





    <table cellspacing="0" cellpadding="5" width="100%">
        <thead>
            <tr style="background:#ddd;">
                <th>Kode Rekening</th>
                <th>Uraian</th>
                <th>Sub Uraian</th>
                <th colspan="4">Saldo Awal {{ $periodeText }}</th>
                <th colspan="4">Pembelian</th>
                <th colspan="4">Saldo Akhir</th>
                <th colspan="4">Persediaan yang Rusak</th>
                <th colspan="4">Beban Persediaan</th>
            </tr>
            <tr style="background:#eee;">
                <th></th>
                <th></th>
                <th></th>
                @for ($i = 0; $i < 5; $i++)
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Total</th>
                @break
            @endfor
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Total</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Total</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Total</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($grouped as $uraian => $data)
            @php
                $first = $data['items'][0];
                $totals = $data['totals'];
            @endphp

            {{-- Baris utama pakai total --}}
            <tr style="font-weight:bold;background:#f0f0f0;">
                <td class="left">{{ $first->kode_rekening }}</td>
                <td class="left">{{ $first->uraian }}</td>
                <td></td>

                {{-- Saldo Awal --}}
                <td colspan="4" style="text-align:right;">
                    {{ fmt($totals['saldo_awal_total']) }}
                </td>

                {{-- Pembelian --}}
                <td colspan="4" style="text-align:right;">
                    {{ fmt($totals['pembelian_total']) }}
                </td>

                {{-- Saldo Akhir --}}
                <td colspan="4" style="text-align:right;">
                    {{ fmt($totals['saldo_akhir_total']) }}
                </td>

                {{-- Rusak --}}
                <td colspan="4" style="text-align:right;">
                    {{ fmtRusak($totals['rusak_total']) }}
                </td>

                {{-- Beban --}}
                <td colspan="4" style="text-align:right;">
                    {{ fmt($totals['beban_total']) }}
                </td>
            </tr>

            {{-- Sub uraian --}}
            @foreach ($data['items'] as $item)
                @if ($item->sub_uraian)
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="left">{{ $item->sub_uraian }}</td>

                        {{-- Saldo Awal --}}
                        @if ($item->saldo_awal_mode == 'total')
                            <td colspan="4" class="merge-cell" style="text-align:right;">
                                {{ fmt($item->saldo_awal_total) }}
                            </td>
                        @else
                            <td>{{ number_format($item->saldo_awal_jumlah) }}</td>
                            <td>{{ $item->saldo_awal_satuan }}</td>
                            <td style="text-align:center;">
                                {{ number_format($item->saldo_awal_harga, 0, ',', '.') }}</td>
                            <td style="text-align:center;">{{ fmt($item->saldo_awal_total) }}</td>
                        @endif

                        {{-- Pembelian --}}
                        @if ($item->pembelian_mode == 'total')
                            <td colspan="4" class="merge-cell" style="text-align:right;">
                                {{ fmt($item->pembelian_total) }}
                            </td>
                        @else
                            <td>{{ number_format($item->pembelian_jumlah) }}</td>
                            <td>{{ $item->pembelian_satuan }}</td>
                            <td style="text-align:center;">{{ number_format($item->pembelian_harga, 0, ',', '.') }}
                            </td>
                            <td style="text-align:center;">{{ fmt($item->pembelian_total) }}</td>
                        @endif

                        {{-- Saldo Akhir --}}
                        @if ($item->saldo_akhir_mode == 'total')
                            <td colspan="4" class="merge-cell" style="text-align:right;">
                                {{ fmt($item->saldo_akhir_total) }}
                            </td>
                        @else
                            <td>{{ number_format($item->saldo_akhir_jumlah) }}</td>
                            <td>{{ $item->saldo_akhir_satuan }}</td>
                            <td style="text-align:center;">
                                {{ number_format($item->saldo_akhir_harga, 0, ',', '.') }}</td>
                            <td style="text-align:center;">{{ fmt($item->saldo_akhir_total) }}</td>
                        @endif

                        {{-- Rusak --}}
                        @if ($item->rusak_mode == 'total')
                            <td colspan="4" class="merge-cell" style="text-align:right;">
                                {{ fmtRusak($item->rusak_total) }}
                            </td>
                        @else
                            <td>{{ fmtRusak($item->rusak_jumlah) }}</td>
                            <td>{{ $item->rusak_satuan }}</td>
                            <td style="text-align:center;">{{ fmtRusak($item->rusak_harga) }}</td>
                            <td style="text-align:center;">{{ fmtRusak($item->rusak_total) }}</td>
                        @endif

                        {{-- Beban --}}
                        {{-- Beban --}}
                        @if ($item->beban_mode == 'total')
                            <td colspan="4" class="merge-cell" style="text-align:right;">
                                {{ fmt($item->beban_total) }}
                            </td>
                        @else
                            <td>{{ number_format($item->beban_jumlah) }}</td>
                            <td>{{ $item->beban_satuan }}</td>
                            <td style="text-align:center;">{{ number_format($item->beban_harga, 0, ',', '.') }}
                            </td>
                            <td style="text-align:center;">{{ fmt($item->beban_total) }}</td>
                        @endif

                    </tr>
                @endif
            @endforeach
        @endforeach

    </tbody>



</table>

<table style="width: 100%; margin-top: 50px; border: none;">
    <tr>
        <td style="width: 50%; text-align: center; border: none;">
            Mengetahui<br>CAMAT CIASEM <br><br><br><br><br>
            <p style="text-decoration: underline; white-space: nowrap;">
                E.ZAITHON THOWI ANSHARI.A.P,.M.Si
            </p>
            <p>NIP. 19650421 199003 1 001</p>
        </td>
        <td style="width: 50%; text-align: center; border: none;">
            Ciasem, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
            PENGURUS BARANG<br><br><br><br><br>
            <p style="text-decoration: underline; white-space: nowrap;">
                SUTRISNO
            </p>
            <p>19721228 200701 1 005</p>
        </td>

    </tr>
</table>


</html>
