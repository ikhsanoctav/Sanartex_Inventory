<x-filament-panels::page>
    <div style="display: flex; flex-wrap: wrap; gap: 2rem; align-items: flex-start;">

        <!-- Kolom Kiri: Form -->
        <div style="flex: 1 1 55%; min-width: 320px; background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; padding: 1.5rem;">
            <form wire:submit="simpan">
                {{ $this->form }}

                <div style="margin-top: 1.5rem;">
                    <x-filament::button type="submit" color="success" icon="heroicon-o-arrow-down-circle" style="width: 100%; justify-content: center;">
                        Simpan Stok Masuk
                    </x-filament::button>
                </div>
            </form>
        </div>

        <!-- Kolom Kanan -->
        <div style="flex: 1 1 40%; min-width: 320px; display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Widget Stats -->
            <div style="padding: 1.25rem; background: white; border-radius: 0.5rem; border: 1px solid #e5e7eb; border-left: 4px solid #10b981;">
                <div style="font-size: 0.75rem; font-weight: bold; color: #6b7280; margin-bottom: 0.5rem;">TOTAL MASUK (HARI INI)</div>
                <div style="font-size: 2rem; font-weight: bold; color: #10b981;">{{ number_format($this->totalMasukHariIni) }}</div>
                <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">unit diterima hari ini</div>
            </div>

            <!-- Riwayat Stok Masuk -->
            <div style="background: white; border-radius: 0.5rem; border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-weight: bold; color: #111827;">Riwayat Stok Masuk</h3>
                    <button type="button" wire:click="toggleRiwayat" style="font-size: 0.875rem; color: #374151; font-weight: bold; background: none; border: none; cursor: pointer; text-decoration: underline;">
                        {{ $showAllRiwayat ? 'Tampilkan Lebih Sedikit' : 'Lihat Semua' }}
                    </button>
                </div>
                <div class="overflow-x-auto" style="padding: 1.25rem; text-align: left; color: #111827; font-size: 0.875rem;">
                    @if($this->riwayatMasuk->isEmpty())
                        <div style="text-align: center; color: #6b7280; padding: 1rem 0;">Belum ada riwayat stok masuk.</div>
                    @else
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 1px solid #e5e7eb; color: #6b7280; font-size: 0.75rem;">
                                    <th style="padding-bottom: 0.5rem; text-align: left;">TANGGAL</th>
                                    <th style="padding-bottom: 0.5rem; text-align: left;">PRODUK</th>
                                    <th style="padding-bottom: 0.5rem; text-align: left;">SUMBER</th>
                                    <th style="padding-bottom: 0.5rem; text-align: right;">QTY</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($this->riwayatMasuk as $tx)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 0.75rem 0;">{{ $tx->created_at->format('d/m/Y') }}</td>
                                    <td style="padding: 0.75rem 0; font-weight: bold;">{{ $tx->product->nama ?? 'Unknown' }}</td>
                                    <td style="padding: 0.75rem 0; color: #6b7280; font-size: 0.8rem;">{{ $tx->keterangan ?: '-' }}</td>
                                    <td style="padding: 0.75rem 0; text-align: right; font-weight: bold; color: #10b981;">+{{ $tx->jumlah }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($showAllRiwayat)
                        <div style="margin-top: 1.5rem; text-align: center; border-top: 1px dashed #e5e7eb; padding-top: 1rem;">
                            {{ $this->clearRiwayatAction }}
                        </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Banner Tips -->
            <div style="background-color: #1f2937; border-radius: 0.5rem; overflow: hidden; position: relative; height: 120px;">
                <div style="position: absolute; inset: 0; background: linear-gradient(135deg, #10b981 0%, #065f46 100%); opacity: 0.6;"></div>
                <div style="position: relative; z-index: 10; padding: 1.5rem; display: flex; flex-direction: column; justify-content: center; height: 100%;">
                    <h3 style="color: white; font-weight: bold; font-size: 1.25rem; margin-bottom: 0.25rem;">Tips Penerimaan</h3>
                    <p style="color: #d1d5db; font-size: 0.75rem; max-width: 80%;">Selalu periksa kondisi fisik barang dan cocokkan dengan surat jalan sebelum menyimpan data masuk.</p>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>
