<x-filament-panels::page>
    <div style="display: flex; flex-wrap: wrap; gap: 2rem; align-items: flex-start;">
        
        <!-- Kolom Kiri: Form -->
        <div style="flex: 1 1 55%; min-width: 320px; background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; padding: 1.5rem;">
            <form wire:submit="simpanTransaksi">
                {{ $this->form }}

                <div style="margin-top: 1.5rem;">
                    <x-filament::button type="submit" color="gray" icon="heroicon-o-document-plus" style="width: 100%; justify-content: center;">
                        Simpan Transaksi
                    </x-filament::button>
                </div>
            </form>
        </div>

        <!-- Kolom Kanan: Dashboard Transaksi -->
        <div style="flex: 1 1 40%; min-width: 320px; display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Widget Stats -->
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <div x-on:click="$dispatch('open-modal', { id: 'modal-total-masuk' })" style="flex: 1; min-width: 120px; padding: 1.25rem; background: white; border-radius: 0.5rem; border: 1px solid #e5e7eb; border-left: 4px solid #10b981; cursor: pointer;" title="Klik untuk melihat detail">
                    <div style="font-size: 0.75rem; font-weight: bold; color: #6b7280; margin-bottom: 0.5rem;">TOTAL MASUK (HARI INI)</div>
                    <div style="font-size: 1.75rem; font-weight: bold; color: #10b981;">{{ number_format($this->totalMasukHariIni) }}</div>
                </div>
                <div x-on:click="$dispatch('open-modal', { id: 'modal-total-keluar' })" style="flex: 1; min-width: 120px; padding: 1.25rem; background: white; border-radius: 0.5rem; border: 1px solid #e5e7eb; border-left: 4px solid #ef4444; cursor: pointer;" title="Klik untuk melihat detail">
                    <div style="font-size: 0.75rem; font-weight: bold; color: #6b7280; margin-bottom: 0.5rem;">TOTAL KELUAR (HARI INI)</div>
                    <div style="font-size: 1.75rem; font-weight: bold; color: #ef4444;">{{ number_format($this->totalKeluarHariIni) }}</div>
                </div>
                <div x-on:click="$dispatch('open-modal', { id: 'modal-sisa-kritis' })" style="flex: 1; min-width: 120px; padding: 1.25rem; background: white; border-radius: 0.5rem; border: 1px solid #e5e7eb; border-left: 4px solid #dc2626; cursor: pointer;" title="Klik untuk melihat detail">
                    <div style="font-size: 0.75rem; font-weight: bold; color: #6b7280; margin-bottom: 0.5rem;">SISA SKU KRITIS</div>
                    <div style="font-size: 1.75rem; font-weight: bold; color: #dc2626;">{{ number_format($this->sisaSkuKritis) }}</div>
                </div>
            </div>

            <!-- Riwayat Tabel Dummy Sementara -->
            <div style="background: white; border-radius: 0.5rem; border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-weight: bold; color: #111827;">Riwayat Transaksi Terakhir</h3>
                    <button type="button" wire:click="toggleRiwayat" style="font-size: 0.875rem; color: #374151; font-weight: bold; background: none; border: none; cursor: pointer; text-decoration: underline;">
                        {{ $showAllRiwayat ? 'Tampilkan Lebih Sedikit' : 'Lihat Semua' }}
                    </button>
                </div>
                <div class="overflow-x-auto" style="padding: 1.25rem; text-align: left; color: #111827; font-size: 0.875rem;">
                    @if($this->riwayatTransaksi->isEmpty())
                        <div style="text-align: center; color: #6b7280; padding: 1rem 0;">Belum ada riwayat transaksi.</div>
                    @else
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 1px solid #e5e7eb; color: #6b7280; font-size: 0.75rem;">
                                    <th style="padding-bottom: 0.5rem; text-align: left;">TANGGAL</th>
                                    <th style="padding-bottom: 0.5rem; text-align: left;">PRODUK</th>
                                    <th style="padding-bottom: 0.5rem; text-align: right;">QTY</th>
                                    <th style="padding-bottom: 0.5rem; text-align: center;">TIPE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($this->riwayatTransaksi as $tx)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 0.75rem 0;">{{ $tx->created_at->format('d/m/Y') }}</td>
                                    <td style="padding: 0.75rem 0; font-weight: bold;">{{ $tx->product->nama ?? 'Unknown' }}</td>
                                    <td style="padding: 0.75rem 0; text-align: right;">{{ $tx->jumlah }}</td>
                                    <td style="padding: 0.75rem 0; text-align: center;">
                                        @if($tx->tipe === 'MASUK')
                                            <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.7rem; font-weight: bold;">MASUK</span>
                                        @else
                                            <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.7rem; font-weight: bold;">KELUAR</span>
                                        @endif
                                    </td>
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
                <div style="position: absolute; inset: 0; background-image: url('https://images.unsplash.com/photo-1586528116311-ad8c738759be?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'); background-size: cover; background-position: center; opacity: 0.4;"></div>
                <div style="position: relative; z-index: 10; padding: 1.5rem; display: flex; flex-direction: column; justify-content: center; height: 100%;">
                    <h3 style="color: white; font-weight: bold; font-size: 1.25rem; margin-bottom: 0.25rem;">Tips Gudang</h3>
                    <p style="color: #d1d5db; font-size: 0.75rem; max-width: 80%;">Selalu periksa label SKU sebelum melakukan validasi transaksi keluar untuk menghindari kesalahan input.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Modals -->
    <x-filament::modal id="modal-total-masuk" width="2xl">
        <x-slot name="heading">Daftar Produk Masuk (Hari Ini)</x-slot>
        <div style="overflow-x: auto; margin-top: 1rem;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb; color: #6b7280;">
                        <th style="padding: 0.75rem;">No</th>
                        <th style="padding: 0.75rem;">Kode Produk</th>
                        <th style="padding: 0.75rem;">Nama Produk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->listTotalMasuk as $index => $product)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 0.75rem;">{{ $index + 1 }}</td>
                            <td style="padding: 0.75rem; font-family: monospace;">PRD-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td style="padding: 0.75rem; font-weight: bold;">{{ $product->nama }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding: 1rem; text-align: center; color: #6b7280; font-style: italic;">Tidak ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::modal>

    <x-filament::modal id="modal-total-keluar" width="2xl">
        <x-slot name="heading">Daftar Produk Keluar (Hari Ini)</x-slot>
        <div style="overflow-x: auto; margin-top: 1rem;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb; color: #6b7280;">
                        <th style="padding: 0.75rem;">No</th>
                        <th style="padding: 0.75rem;">Kode Produk</th>
                        <th style="padding: 0.75rem;">Nama Produk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->listTotalKeluar as $index => $product)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 0.75rem;">{{ $index + 1 }}</td>
                            <td style="padding: 0.75rem; font-family: monospace;">PRD-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td style="padding: 0.75rem; font-weight: bold;">{{ $product->nama }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding: 1rem; text-align: center; color: #6b7280; font-style: italic;">Tidak ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::modal>

    <x-filament::modal id="modal-sisa-kritis" width="2xl">
        <x-slot name="heading">Daftar Sisa SKU Kritis</x-slot>
        <div style="overflow-x: auto; margin-top: 1rem;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb; color: #6b7280;">
                        <th style="padding: 0.75rem;">No</th>
                        <th style="padding: 0.75rem;">Kode Produk</th>
                        <th style="padding: 0.75rem;">Nama Produk</th>
                        <th style="padding: 0.75rem; text-align: right;">Sisa Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->listSisaSkuKritis as $index => $product)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 0.75rem;">{{ $index + 1 }}</td>
                            <td style="padding: 0.75rem; font-family: monospace;">PRD-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td style="padding: 0.75rem; font-weight: bold;">{{ $product->nama }}</td>
                            <td style="padding: 0.75rem; text-align: right; font-weight: bold; color: #dc2626;">{{ $product->stok_aktual }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 1rem; text-align: center; color: #6b7280; font-style: italic;">Tidak ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::modal>
</x-filament-panels::page>
