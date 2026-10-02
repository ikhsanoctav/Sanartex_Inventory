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
        .custom-grid { display: grid; gap: 1.5rem; grid-template-columns: 1fr; }
        @media (min-width: 640px) { .custom-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .custom-grid { grid-template-columns: repeat(3, 1fr); } }
    </style>
    <div x-data="{ openModal: null }" class="custom-grid">
        
        <!-- NORMAL -->
        <x-filament::card @click="openModal = 'normal'" style="cursor: pointer;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="padding: 0.75rem; background-color: #dcfce7; color: #16a34a; border-radius: 0.5rem;">
                    <x-heroicon-o-check-circle style="width: 1.5rem; height: 1.5rem;" />
                </div>
                <div>
                    <h3 style="font-size: 0.875rem; font-weight: 500; color: #6b7280; margin: 0;">STATUS NORMAL</h3>
                    <div style="font-size: 1.5rem; font-weight: bold; color: #111827;">{{ $normalCount }} SKU</div>
                </div>
            </div>
        </x-filament::card>

        <!-- KRITIS -->
        <x-filament::card @click="openModal = 'kritis'" style="cursor: pointer;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="padding: 0.75rem; background-color: #fee2e2; color: #dc2626; border-radius: 0.5rem;">
                    <x-heroicon-o-exclamation-triangle style="width: 1.5rem; height: 1.5rem;" />
                </div>
                <div>
                    <h3 style="font-size: 0.875rem; font-weight: 500; color: #6b7280; margin: 0;">STOK KRITIS</h3>
                    <div style="font-size: 1.5rem; font-weight: bold; color: #111827;">{{ $kritisCount }} SKU</div>
                </div>
            </div>
        </x-filament::card>

        <!-- BERLEBIH -->
        <x-filament::card @click="openModal = 'berlebih'" style="cursor: pointer;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="padding: 0.75rem; background-color: #dbeafe; color: #2563eb; border-radius: 0.5rem;">
                    <x-heroicon-o-archive-box style="width: 1.5rem; height: 1.5rem;" />
                </div>
                <div>
                    <h3 style="font-size: 0.875rem; font-weight: 500; color: #6b7280; margin: 0;">STOK BERLEBIH</h3>
                    <div style="font-size: 1.5rem; font-weight: bold; color: #111827;">{{ $berlebihCount }} SKU</div>
                </div>
            </div>
        </x-filament::card>

        <!-- Modals -->
        @foreach([
            ['id' => 'normal', 'title' => 'STATUS NORMAL', 'products' => $normalProducts],
            ['id' => 'kritis', 'title' => 'STOK KRITIS', 'products' => $kritisProducts],
            ['id' => 'berlebih', 'title' => 'STOK BERLEBIH', 'products' => $berlebihProducts],
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
