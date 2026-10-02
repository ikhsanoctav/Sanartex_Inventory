<x-filament-widgets::widget>
    <style>
        .custom-modal-overlay {
            position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 50;
            display: flex; align-items: center; justify-content: center;
            background-color: rgba(17, 24, 39, 0.5); padding: 1rem;
        }
        .custom-modal-box {
            width: 100%; max-width: 32rem; background-color: #ffffff;
            border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .custom-modal-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1rem; border-bottom: 1px solid #e5e7eb;
        }
        .custom-modal-body {
            padding: 1rem; max-height: 24rem; overflow-y: auto;
        }
        .custom-table { width: 100%; text-align: left; border-collapse: collapse; }
        .custom-table th { padding: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #4b5563; border-bottom: 1px solid #e5e7eb; background-color: #f9fafb; }
        .custom-table td { padding: 0.5rem; font-size: 0.875rem; color: #6b7280; border-bottom: 1px solid #f3f4f6; }
    </style>
    
    <div x-data="{ openModal: null }" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">
        
        <div @click="openModal = 'total'" style="padding: 1.25rem; background: white; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; cursor: pointer;">
            <div style="font-size: 0.75rem; font-weight: bold; color: #6b7280; letter-spacing: 0.05em; margin-bottom: 0.5rem;">TOTAL SKU</div>
            <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                <span style="font-size: 2rem; font-weight: bold; color: #111827;">{{ number_format($totalSku) }}</span>
                <span style="font-size: 0.75rem; font-weight: bold; color: #047857;">+12 bln ini</span>
            </div>
        </div>

        <div @click="openModal = 'kritis'" style="padding: 1.25rem; background: white; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; border-left: 4px solid #f59e0b; cursor: pointer;">
            <div style="font-size: 0.75rem; font-weight: bold; color: #6b7280; letter-spacing: 0.05em; margin-bottom: 0.5rem;">STOK KRITIS</div>
            <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                <span style="font-size: 2rem; font-weight: bold; color: #d97706;">{{ number_format($kritis) }}</span>
                <span style="font-size: 0.75rem; font-weight: bold; color: white; background-color: #f59e0b; padding: 0.125rem 0.5rem; border-radius: 9999px;">Perlu Restock</span>
            </div>
        </div>

        <div @click="openModal = 'berlebih'" style="padding: 1.25rem; background: white; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; border-left: 4px solid #ef4444; cursor: pointer;">
            <div style="font-size: 0.75rem; font-weight: bold; color: #6b7280; letter-spacing: 0.05em; margin-bottom: 0.5rem;">STOK BERLEBIH</div>
            <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                <span style="font-size: 2rem; font-weight: bold; color: #ef4444;">{{ number_format($berlebih) }}</span>
                <span style="font-size: 0.75rem; font-style: italic; color: #6b7280;">Overstock</span>
            </div>
        </div>

        <div @click="openModal = 'normal'" style="padding: 1.25rem; background: white; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; border-left: 4px solid #10b981; cursor: pointer;">
            <div style="font-size: 0.75rem; font-weight: bold; color: #6b7280; letter-spacing: 0.05em; margin-bottom: 0.5rem;">STOK NORMAL</div>
            <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                <span style="font-size: 2rem; font-weight: bold; color: #10b981;">{{ number_format($normal) }}</span>
                <span style="font-size: 0.75rem; color: #6b7280;">Aman</span>
            </div>
        </div>

        <!-- Modals -->
        @foreach([
            ['id' => 'total', 'title' => 'TOTAL SKU', 'products' => $totalSkuProducts ?? collect()],
            ['id' => 'kritis', 'title' => 'STOK KRITIS', 'products' => $kritisProducts ?? collect()],
            ['id' => 'berlebih', 'title' => 'STOK BERLEBIH', 'products' => $berlebihProducts ?? collect()],
            ['id' => 'normal', 'title' => 'STOK NORMAL', 'products' => $normalProducts ?? collect()],
        ] as $modal)
            <div x-show="openModal === '{{ $modal['id'] }}'" style="display: none;" class="custom-modal-overlay" x-transition>
                <div @click.outside="openModal = null" class="custom-modal-box">
                    <div class="custom-modal-header">
                        <h3 style="font-weight: bold; font-size: 1.125rem; margin: 0;">Daftar Produk: {{ $modal['title'] }}</h3>
                        <button @click="openModal = null" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">&times;</button>
                    </div>
                    <div class="custom-modal-body">
                        @if($modal['products']->isEmpty())
                            <p style="color: #6b7280; font-size: 0.875rem; font-style: italic; text-align: center;">Tidak ada produk di kategori ini.</p>
                        @else
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th style="width: 3rem; text-align: center;">No</th>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modal['products'] as $index => $p)
                                        <tr>
                                            <td style="text-align: center;">{{ $index + 1 }}</td>
                                            <td style="font-family: monospace;">STX-KTN-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</td>
                                            <td style="font-weight: 600; color: #111827;">{{ $p->nama }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</x-filament-widgets::widget>
