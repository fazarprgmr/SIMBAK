@extends('layouts.app')
@section('title', ($rka->exists ? 'Edit' : 'Tambah') . ' RKA')

@section('content')
    <form method="POST" action="{{ $rka->exists ? route('rka.update', $rka) : route('rka.store') }}">
        @csrf
        @if ($rka->exists)
            @method('PUT')
        @endif

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">{{ $rka->exists ? 'Edit' : 'Tambah' }} Data RKA</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label class="form-label">Kode Rekening</label>
                        <select name="kode_rekening" class="form-select">
                            <option value="">-- Tidak Ada / Kosong --</option>
                            @foreach ($kodeRekenings as $kr)
                                <option value="{{ $kr->kode }}"
                                    {{ old('kode_rekening', $rka->kode_rekening) == $kr->kode ? 'selected' : '' }}>
                                    {{ $kr->kode }} - {{ $kr->uraian }}
                                </option>
                            @endforeach
                        </select>
                        @error('kode_rekening')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Sub Uraian</label>
                        <input class="form-control" name="sub_uraian" value="{{ old('sub_uraian', $rka->sub_uraian) }}">
                    </div>
                </div>

                @php
                    $sections = [
                        'saldo_awal' => 'Saldo Awal',
                        'pembelian' => 'Pembelian',
                        'saldo_akhir' => 'Saldo Akhir',
                        'rusak' => 'Persediaan yang Rusak',
                        'beban' => 'Beban Persediaan',
                    ];
                @endphp


                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:200px">Bagian</th>
                                <th style="width:120px">Mode Input</th>
                                <th style="width:100px">Jumlah</th>
                                <th style="width:120px">Satuan</th>
                                <th style="width:120px">Harga</th>
                                <th style="width:150px">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sections as $key => $label)
                                <tr>
                                    <td><strong>{{ $label }}</strong></td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="{{ $key }}_mode"
                                                value="total"
                                                {{ old("{$key}_mode", $rka->{"{$key}_mode"}) == 'total' ? 'checked' : '' }}>
                                            <label class="form-check-label">Total</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="{{ $key }}_mode"
                                                value="detail"
                                                {{ old("{$key}_mode", $rka->{"{$key}_mode"}) == 'detail' ? 'checked' : '' }}>

                                            <label class="form-check-label">Detail</label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="{{ $key }}_jumlah"
                                            class="form-control detail-input-{{ $key }}"
                                            value="{{ old("{$key}_jumlah", $rka->{"{$key}_jumlah"}) }}"
                                            @if ($key === 'beban') readonly @endif>
                                    </td>
                                    <td>
                                        <select name="{{ $key }}_satuan"
                                            class="form-select detail-input-{{ $key }}"
                                            @if ($key === 'beban') disabled @endif>
                                            <option value="">-- Pilih Satuan --</option>
                                            @foreach ($satuans as $satuan)
                                                <option value="{{ $satuan->nama }}"
                                                    {{ old("{$key}_satuan", $rka->{"{$key}_satuan"}) == $satuan->nama ? 'selected' : '' }}>
                                                    {{ $satuan->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="{{ $key }}_harga"
                                            class="form-control detail-input-{{ $key }}"
                                            value="{{ old("{$key}_harga", $rka->{"{$key}_harga"}) }}"
                                            @if ($key === 'beban') readonly @endif>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="{{ $key }}_total"
                                            class="form-control total-input-{{ $key }}"
                                            value="{{ old("{$key}_total", $rka->{"{$key}_total"}) }}"
                                            @if ($key === 'beban') readonly @endif>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('rka.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>




    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sections = @json(array_keys($sections));

            // Ambil mode per baris
            const getMode = key =>
                document.querySelector(`input[name="${key}_mode"]:checked`)?.value || 'detail';

            // Toggle readonly field sesuai mode
            function toggleInputsFor(key) {
                const mode = getMode(key);
                const detailInputs = document.querySelectorAll(`.detail-input-${key}`);
                const totalInput = document.querySelector(`.total-input-${key}`);
                const satuanSelect = document.querySelector(`select[name="${key}_satuan"]`);

                if (key === 'beban') {
                    detailInputs.forEach(el => el.setAttribute('readonly', 'readonly'));
                    if (totalInput) totalInput.setAttribute('readonly', 'readonly');
                    if (satuanSelect) satuanSelect.setAttribute('disabled', 'disabled');
                    return;
                }

                if (mode === 'detail') {
                    detailInputs.forEach(el => el.removeAttribute('readonly'));
                    if (totalInput) totalInput.setAttribute('readonly', 'readonly');
                    if (satuanSelect) satuanSelect.removeAttribute('disabled');
                } else {
                    detailInputs.forEach(el => el.setAttribute('readonly', 'readonly'));
                    if (totalInput) totalInput.removeAttribute('readonly');
                    if (satuanSelect) satuanSelect.setAttribute('disabled', 'disabled');
                }
            }


            function toggleInputsAll() {
                sections.forEach(toggleInputsFor);
            }

            // Hitung total per baris
            // Hitung total per baris
            function recalc(key) {
                if (key === 'beban') return;

                const jumlah = parseFloat(document.querySelector(`[name="${key}_jumlah"]`)?.value) || 0;
                const harga = parseFloat(document.querySelector(`[name="${key}_harga"]`)?.value) || 0;
                const totalEl = document.querySelector(`[name="${key}_total"]`);

                if (getMode(key) === 'detail') {
                    // kalau detail → jumlah * harga
                    if (totalEl) totalEl.value = Math.round(jumlah * harga);
                } else {
                    // kalau total → biarin manual (jangan nol-in)
                    if (totalEl && totalEl.value === '') {
                        totalEl.value = 0;
                    }
                }
            }


            // Hitung beban persediaan
            function computeBeban() {
                // Ambil JUMLAH
                const saJ = parseFloat(document.querySelector(`[name="saldo_awal_jumlah"]`)?.value) || 0;
                const pbJ = parseFloat(document.querySelector(`[name="pembelian_jumlah"]`)?.value) || 0;
                const saAJ = parseFloat(document.querySelector(`[name="saldo_akhir_jumlah"]`)?.value) || 0;
                const rsJ = parseFloat(document.querySelector(`[name="rusak_jumlah"]`)?.value) || 0;

                // Ambil TOTAL
                const saT = parseFloat(document.querySelector(`[name="saldo_awal_total"]`)?.value) || 0;
                const pbT = parseFloat(document.querySelector(`[name="pembelian_total"]`)?.value) || 0;
                const saAT = parseFloat(document.querySelector(`[name="saldo_akhir_total"]`)?.value) || 0;
                const rsT = parseFloat(document.querySelector(`[name="rusak_total"]`)?.value) || 0;

                // Hitung jumlah & total beban
                const bebanJumlah = saJ + pbJ - saAJ - rsJ;
                const bebanTotal = saT + pbT - saAT - rsT;

                // Isi field
                const bebanJumlahEl = document.querySelector(`[name="beban_jumlah"]`);
                const bebanTotalEl = document.querySelector(`[name="beban_total"]`);
                const bebanHargaEl = document.querySelector(`[name="beban_harga"]`);

                if (bebanJumlahEl) bebanJumlahEl.value = bebanJumlah.toFixed(2);
                if (bebanTotalEl) bebanTotalEl.value = bebanTotal.toFixed(2);

                // Harga beban = ikut saldo awal
                const harga = parseFloat(document.querySelector(`[name="saldo_awal_harga"]`)?.value) || 0;
                if (bebanHargaEl) {
                    bebanHargaEl.value = harga;
                    bebanHargaEl.setAttribute('readonly', 'readonly');
                }
            }


            function recalcAll() {
                sections.forEach(k => recalc(k));
                computeBeban();
            }

            // ---- Sinkron harga awal (sekali saat load) ----
            const masterHarga = document.querySelector(`[name="saldo_awal_harga"]`);

            function syncHargaFromMaster() {
                const val = masterHarga?.value || '';
                ['pembelian', 'saldo_akhir', 'rusak'].forEach(key => {
                    const h = document.querySelector(`[name="${key}_harga"]`);
                    if (h && !h.dataset.manual) { // hanya update kalau belum manual
                        h.value = val;
                        recalc(key);
                    }
                });
                computeBeban();
            }
            if (masterHarga) {
                masterHarga.addEventListener('input', syncHargaFromMaster);
                masterHarga.addEventListener('change', syncHargaFromMaster);
            }

            // Tandai field harga yang diubah manual supaya tidak override
            ['pembelian', 'saldo_akhir', 'rusak'].forEach(key => {
                const h = document.querySelector(`[name="${key}_harga"]`);
                if (h) {
                    h.addEventListener('input', () => h.dataset.manual = true);
                }
            });

            // ---- Sinkron satuan awal (sekali saat load) ----
            const masterSatuan = document.querySelector(`[name="saldo_awal_satuan"]`);

            function syncSatuanFromMaster() {
                const val = masterSatuan?.value || '';
                ['pembelian', 'saldo_akhir', 'rusak', 'beban'].forEach(key => {
                    const s = document.querySelector(`[name="${key}_satuan"]`);
                    if (s && !s.dataset.manual) {
                        s.value = val;
                    }
                });
            }
            if (masterSatuan) {
                masterSatuan.addEventListener('change', syncSatuanFromMaster);
            }

            // Tandai satuan yang diubah manual supaya tidak override
            ['pembelian', 'saldo_akhir', 'rusak', ].forEach(key => {
                const s = document.querySelector(`[name="${key}_satuan"]`);
                if (s) {
                    s.addEventListener('change', () => s.dataset.manual = true);
                }
            });

            // ---- Sinkron mode (live ikutin saldo_awal) ----
            function syncModeFromMaster() {
                const mode = document.querySelector(`input[name="saldo_awal_mode"]:checked`)?.value;
                if (!mode) return;
                ['pembelian', 'saldo_akhir', 'rusak', 'beban'].forEach(key => {
                    document.querySelectorAll(`input[name="${key}_mode"]`).forEach(r => {
                        r.checked = (r.value === mode);
                    });
                });
                toggleInputsAll();
                recalcAll();
            }
            document.querySelectorAll(`input[name="saldo_awal_mode"]`).forEach(r => {
                r.addEventListener('change', syncModeFromMaster);
            });

            // ---- Listener jumlah, harga, dan total -> hitung ulang ----
            sections.forEach(key => {
                ['jumlah', 'harga', 'total'].forEach(s => {
                    const el = document.querySelector(`[name="${key}_${s}"]`);
                    if (!el) return;
                    el.addEventListener('input', () => {
                        recalc(key);
                        computeBeban();
                    });
                    el.addEventListener('change', () => {
                        recalc(key);
                        computeBeban();
                    });
                });
            });


            // Init awal
            syncModeFromMaster();
            toggleInputsAll();
            recalcAll();
        });
    </script>



@endsection
