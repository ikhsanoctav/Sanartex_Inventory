# Software Design Document (SDD)
## Arsitektur & Desain Sistem Inventori Tekstil Berbasis Rule-Based Logic & Buffer Level (RBL)
**PT Saranatex (Sanartex)**

---

| **Standar Dokumen** | Mengacu pada IEEE Std 1016-2009 (*Systems and software engineering — Software design descriptions*) |
|---|---|
| **Dokumen ID** | SDD-SANARTEX-INV-01 |
| **Versi** | 2.0.0 (Redesain Berbasis RBL) |
| **Status** | Approved |
| **Tanggal Terbit** | 2 Oktober 2026 |
| **Platform** | Laravel 11/12, Filament v3, Livewire 3, Alpine.js, Tailwind CSS |

---

## 1. Pendahuluan & Tujuan Arsitektur

### 1.1 Tujuan
Dokumen Desain Perangkat Lunak (*Software Design Document* - SDD) ini mendefinisikan arsitektur tingkat tinggi (*high-level*), desain komponen terperinci (*low-level*), skema basis data (*database schema*), serta mekanisme logika bisnis **Rule-Based Logic & Buffer Level (RBL)** yang diterapkan pada sistem inventori PT Saranatex.

### 1.2 Prinsip Desain
1. **Separation of Concerns (SoC):** Pemisahan yang jelas antara lapisan antarmuka (*Presentation/Filament*), logika inferensi bisnis (*Domain Service/RBL Engine*), dan akses data (*Eloquent ORM / Repository*).
2. **Data Consistency & ACID Compliance:** Menjamin integritas perhitungan stok mutlak menggunakan transaksi basis data (*database transaction*) dan penanganan kondisi balapan (*race condition*).
3. **Reactive & Real-Time Feedback:** Perhitungan status buffer dan indikator visual bereaksi seketika saat data transaksi disimpan.
4. **Maintainability & Extensibility:** Kode dirancang modular agar mudah ditambahkan fungsionalitas baru seperti integrasi barcode scanner 2D, purchase order otomatis, atau multi-gudang.

---

## 2. Arsitektur Sistem Tingkat Tinggi (High-Level Architecture)

Aplikasi dibangun menggunakan arsitektur **Layered Modular MVC** yang dioptimalkan dengan **Filament v3 Component Architecture**:

```mermaid
graph TD
    subgraph Client Layer [Presentation Layer / Client Browser]
        UI_Desktop[Web Browser Desktop]
        UI_Tablet[Tablet Gudang / Mobile View]
    end

    subgraph Presentation & Controller Layer [Filament v3 & Livewire]
        Panel[AdminPanelProvider]
        Pages[Custom Pages: TransaksiStok, Laporan, Panduan]
        Resources[Filament Resources: ProductResource, StockIn, StockOut]
        Widgets[Widgets: InventorySummaryWidget, ProductsAttentionWidget]
    end

    subgraph Domain & Business Logic Layer [Services & Observers]
        RBL_Engine[RblEvaluatorService / Buffer Calculator]
        Stock_Service[StockTransactionService]
        Notif_Service[InventoryNotificationService]
        Model_Events[Eloquent Model Boot Events / Observers]
    end

    subgraph Data Access Layer [Eloquent ORM]
        M_User[User Model]
        M_Product[Product Model]
        M_StockIn[StockInTransaction Model]
        M_StockOut[StockOutTransaction Model]
        M_Notif[Notification Model]
    end

    subgraph Storage Layer [Database & Cache]
        DB[(Relational DB: SQLite / MySQL / PostgreSQL)]
        Cache[(Application Cache)]
    end

    UI_Desktop & UI_Tablet --> Panel
    Panel --> Pages & Resources & Widgets
    Pages & Resources & Widgets --> Stock_Service & RBL_Engine
    Stock_Service --> Model_Events
    Model_Events --> RBL_Engine & Notif_Service
    RBL_Engine & Stock_Service & Notif_Service --> M_Product & M_StockIn & M_StockOut & M_User & M_Notif
    M_Product & M_StockIn & M_StockOut & M_User & M_Notif --> DB
```

---

## 3. Desain Logika Bisnis: Mesin Inferensi RBL (Rule-Based Logic)

### 3.1 Konsep Zonasi Buffer Tekstil (3-Zone RBL Model)
Sistem membagi tingkat persediaan ke dalam 3 zona buffer yang dievaluasi berurutan:

```mermaid
stateDiagram-v2
    [*] --> Evaluasi_RBL
    Evaluasi_RBL --> ZONA_MERAH : Stok <= Batas Minimum (SS)
    Evaluasi_RBL --> ZONA_HIJAU : Batas Minimum < Stok <= Batas Maksimum
    Evaluasi_RBL --> ZONA_BIRU : Stok > Batas Maksimum

    state ZONA_MERAH {
        [*] --> Kritis_Urgent
        Kritis_Urgent: Label KRITIS (Red Badge)
        Kritis_Urgent: Trigger Urgent Reorder Alert
        Kritis_Urgent: Hitung Rekomendasi Order Maksimal (Max - Stok)
    }

    state ZONA_HIJAU {
        [*] --> Kondisi_Optimal
        Kondisi_Optimal: Label NORMAL (Green Badge)
        Kondisi_Optimal: Operasional Persediaan Aman & Terkendali
    }

    state ZONA_BIRU {
        [*] --> Overstock_Warning
        Overstock_Warning: Label BERLEBIH (Blue Badge)
        Overstock_Warning: Hold Purchase Order Baru
    }
```

### 3.2 Algoritma Evaluasi & Pseudocode RBL
```php
class RblEvaluatorService
{
    public static function evaluate(Product $product): string
    {
        $stok = $product->stok_aktual;
        $min = $product->batas_minimum;
        $max = $product->batas_maksimum;

        if ($stok <= $min) {
            return 'KRITIS';   // Zona Merah
        } elseif ($stok <= $max) {
            return 'NORMAL';   // Zona Hijau
        } else {
            return 'BERLEBIH'; // Zona Biru
        }
    }
}
```

    public static function calculateSuggestedOrder(Product $product): int
    {
        if (in_array($product->status_stok, ['KRITIS', 'WASPADA'])) {
            return max(0, $product->batas_maksimum - $product->stok_aktual);
        }
        return 0;
    }
}
```

---

## 4. Desain Basis Data (Database Design & ERD)

### 4.1 Entity Relationship Diagram (ERD)

```mermaid
erDiagram
    USERS ||--o{ STOCK_IN_TRANSACTIONS : "dibuat oleh"
    USERS ||--o{ STOCK_OUT_TRANSACTIONS : "dibuat oleh"
    PRODUCTS ||--o{ STOCK_IN_TRANSACTIONS : "menerima transaksi masuk"
    PRODUCTS ||--o{ STOCK_OUT_TRANSACTIONS : "menerima transaksi keluar"
    PRODUCTS ||--o{ PRODUCT_RBL_HISTORIES : "merekam riwayat buffer"

    USERS {
        bigint id PK
        string name
        string email
        string password
        string role
        timestamp created_at
        timestamp updated_at
    }

    PRODUCTS {
        bigint id PK
        string kode_produk UK "Kode SKU Tekstil unik"
        string nama "Nama kain / benang"
        string kategori "Kategori (Katun, Rayon, TC, Benang, dll)"
        string satuan "Satuan dasar (Roll, Meter, Yard, Kg, Pcs)"
        integer stok_aktual "Jumlah stok saat ini"
        integer batas_minimum "Ambang kritis (Safety Stock)"
        integer reorder_point "Titik pemesanan ulang (ROP)"
        integer batas_maksimum "Kapasitas aman maksimum"
        integer lead_time_days "Lead time supplier (hari)"
        string status_stok "KRITIS | WASPADA | NORMAL | BERLEBIH"
        decimal harga_beli_per_satuan "Valuasi harga beli"
        string lokasi_rak "Posisi penyimpanan gudang"
        text keterangan "Catatan spesifikasi kain"
        timestamp created_at
        timestamp updated_at
    }

    STOCK_IN_TRANSACTIONS {
        bigint id PK
        bigint product_id FK
        bigint user_id FK
        date tanggal_masuk
        integer jumlah_masuk
        string no_surat_jalan
        string supplier
        string no_batch_lot "Lot pewarnaan tekstil"
        text keterangan
        timestamp created_at
        timestamp updated_at
    }

    STOCK_OUT_TRANSACTIONS {
        bigint id PK
        bigint product_id FK
        bigint user_id FK
        date tanggal_keluar
        integer jumlah_keluar
        string no_spk_tujuan "Nomor SPK / Surat Jalan Keluar"
        string penerima_divisi "Divisi/Customer tujuan"
        text keterangan
        timestamp created_at
        timestamp updated_at
    }

    PRODUCT_RBL_HISTORIES {
        bigint id PK
        bigint product_id FK
        date tanggal_evaluasi
        integer stok_sebelum
        integer stok_sesudah
        string status_sebelum
        string status_sesudah
        string pemicu_transaksi "INBOUND | OUTBOUND | MANUAL"
        timestamp created_at
    }
```

### 4.2 Struktur Tabel Detail & Kamus Data

#### Tabel `products`
| Kolom | Tipe Data | Keterangan & Aturan |
|---|---|---|
| `id` | BIGINT UNSIGNED (PK, AI) | Identifier unik produk |
| `kode_produk` | VARCHAR(50) (UNIQUE, Index) | Kode unik SKU kain (misal: `KTN-RYN-001`) |
| `nama` | VARCHAR(255) | Nama barang (misal: *Katun Twill 30s 58"*) |
| `kategori` | VARCHAR(100) (Index) | Kategori tekstil (*Kain Katun, Rayon, Benang, dll*) |
| `satuan` | VARCHAR(50) | Satuan unit (*Roll, Meter, Yard, Kg, Pcs*) |
| `stok_aktual` | INT | Stok saat ini (Default: 0, Tidak boleh negatif) |
| `batas_minimum` | INT | Ambang batas kritis bawah ($SS$) |
| `reorder_point` | INT (Nullable) | Titik pemesanan ulang ($ROP$) |
| `batas_maksimum` | INT | Kapasitas batas atas buffer ($Max$) |
| `lead_time_days` | INT (Default: 7) | Lead time pengadaan dari supplier (hari) |
| `status_stok` | ENUM / VARCHAR(30) | `KRITIS`, `WASPADA`, `NORMAL`, `BERLEBIH` |
| `lokasi_rak` | VARCHAR(100) (Nullable) | Posisi rak gudang (misal: *Gudang A - Rak 04*) |

#### Tabel `stock_in_transactions`
| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED (PK, AI) | Identifier transaksi masuk |
| `product_id` | BIGINT UNSIGNED (FK) | Relasi ke `products.id` (*cascade delete*) |
| `user_id` | BIGINT UNSIGNED (FK, Nullable) | Relasi ke `users.id` (operator pembuat) |
| `tanggal_masuk` | DATE | Tanggal penerimaan barang fisik |
| `jumlah_masuk` | INT | Kuantitas barang masuk (> 0) |
| `no_surat_jalan` | VARCHAR(100) (Nullable) | Nomor Surat Jalan / Faktur dari Supplier |
| `supplier` | VARCHAR(150) (Nullable) | Nama supplier atau pabrik pencelupan |
| `no_batch_lot` | VARCHAR(100) (Nullable) | Nomor Lot / Batch pencelupan tekstil |
| `keterangan` | TEXT (Nullable) | Catatan kondisi fisik kain |

#### Tabel `stock_out_transactions`
| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED (PK, AI) | Identifier transaksi keluar |
| `product_id` | BIGINT UNSIGNED (FK) | Relasi ke `products.id` (*cascade delete*) |
| `user_id` | BIGINT UNSIGNED (FK, Nullable) | Relasi ke `users.id` (operator pembuat) |
| `tanggal_keluar` | DATE | Tanggal pengeluaran barang fisik |
| `jumlah_keluar` | INT | Kuantitas barang keluar (> 0) |
| `no_spk_tujuan` | VARCHAR(100) (Nullable) | Nomor SPK Konveksi / Faktur Penjualan |
| `penerima_divisi`| VARCHAR(150) (Nullable) | Divisi Cutting / Sewing / Customer |
| `keterangan` | TEXT (Nullable) | Alasan pengeluaran barang |

---

## 5. Diagram Alur Transaksi & Evaluasi RBL (Sequence Diagrams)

### 5.1 Alur Transaksi Stok Masuk & Evaluasi RBL

```mermaid
sequenceDiagram
    autonumber
    actor Admin as Admin Gudang
    participant Page as Form Stok Masuk (Filament)
    participant Service as StockTransactionService
    participant Product as Product Model
    participant RBL as RblEvaluatorService
    participant DB as Relational Database
    participant Notif as Filament Notification

    Admin->>Page: Submit Form Stok Masuk (Product ID, Qty: +50 Roll, Lot #)
    Page->>Service: handleStockIn(data)
    Service->>DB: Begin DB Transaction
    Service->>DB: Insert into stock_in_transactions
    Service->>Product: Lock row for update & increment stok_aktual (+50)
    Service->>RBL: evaluate(product)
    RBL-->>Service: Return status_stok baru (e.g. 'NORMAL')
    Service->>Product: update status_stok = 'NORMAL'
    Service->>DB: Commit DB Transaction
    Service-->>Page: Return Success Result
    Page-->>Admin: Tampilkan Toast Notifikasi "Stok Berhasil Ditambahkan"
    alt Status berubah ke Kritis/Berlebih
        Service->>Notif: Kirim Notifikasi Lonceng ke Kepala Gudang
    end
```

### 5.2 Alur Transaksi Stok Keluar dengan Proteksi Validasi Minus

```mermaid
sequenceDiagram
    autonumber
    actor Admin as Admin Gudang
    participant Page as Form Stok Keluar (Filament)
    participant Service as StockTransactionService
    participant Product as Product Model
    participant RBL as RblEvaluatorService
    participant DB as Relational Database

    Admin->>Page: Submit Form Stok Keluar (Product ID, Qty: 80 Roll)
    Page->>Service: handleStockOut(data)
    Service->>DB: Begin DB Transaction
    Service->>Product: Find & Lock row (stok_aktual = 60)
    alt Stok Aktual < Jumlah Keluar (60 < 80)
        Service->>DB: Rollback DB Transaction
        Service-->>Page: Throw ValidationException: "Stok tidak mencukupi! Sisa: 60"
        Page-->>Admin: Tampilkan Error Dialog (Transaksi Dibatalkan)
    else Stok Cukup (Stok >= Jumlah Keluar)
        Service->>DB: Insert into stock_out_transactions
        Service->>Product: decrement stok_aktual (-80)
        Service->>RBL: evaluate(product)
        RBL-->>Service: Return status_stok baru ('KRITIS')
        Service->>Product: update status_stok = 'KRITIS'
        Service->>DB: Commit DB Transaction
        Service-->>Page: Return Success Result
        Page-->>Admin: Tampilkan Toast Sukses + Peringatan Stok Kritis
    end
```

---

## 6. Desain Keamanan & Penanganan Konkurensi (*Concurrency Control*)

1. **Pessimistic Locking (`lockForUpdate`):**
   Untuk mencegah *race condition* ketika dua operator gudang melakukan pengeluaran barang yang sama secara bersamaan, setiap mutasi stok dieksekusi dalam blok transaksi terisolasi:
   ```php
   DB::transaction(function () use ($productId, $qty) {
       $product = Product::where('id', $productId)->lockForUpdate()->first();
       
       if ($product->stok_aktual < $qty) {
           throw new \Exception("Stok tidak mencukupi.");
       }
       
       $product->stok_aktual -= $qty;
       $product->status_stok = RblEvaluatorService::evaluate($product);
       $product->save();
   });
   ```
2. **Audit Logging & User Traceability:**
   Setiap mutasi barang secara otomatis merekam `user_id` yang sedang terautentikasi melalui session Laravel.

---

## 7. Rencana Integrasi & Strategi Penerapan

1. **Database Migration Strategy:** Migrasi tabel `products` untuk penambahan kolom RBL (`reorder_point`, `lead_time_days`, `lokasi_rak`, `harga_beli_per_satuan`).
2. **Backward Compatibility:** Data stok lama tetap utuh dengan menjalankan migration script pengisian default RBL dari rasio `batas_minimum` dan `batas_maksimum`.
3. **Automated Unit Testing:** Penyusunan test suite pada `tests/Unit/RblEvaluatorTest.php` untuk memvalidasi keempat aturan zona buffer RBL.
