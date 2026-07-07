# Task 8: Master Data & Inventory (Garments & Fabrics)

## Deskripsi
Membangun antarmuka dan logika untuk mengelola master data kategori pakaian (garment categories) serta data inventaris kain (fabrics dan stocks) untuk menunjang kebutuhan pembuatan pesanan dan pencatatan stok.

## Detail Pekerjaan
1. **Pembuatan Migrasi `garment_categories`, `fabrics`, dan `fabric_stocks`**
   - Skema `garment_categories`: `id`, `name`, `description`, `created_at`, `updated_at`.
   - Skema `fabrics`: `id`, `name`, `fabric_type`, `color`, `created_at`, `updated_at`.
   - Skema `fabric_stocks`: `id`, `fabric_id` (FK), `quantity_in_meters`, `last_restock_date`, `created_at`, `updated_at`.
   - Jalankan `php artisan migrate`.

2. **Pembuatan Model**
   - Buat model `GarmentCategory`, `Fabric`, dan `FabricStock`.
   - Tentukan atribut `$fillable` untuk masing-masing model.
   - Atur relasi: `Fabric` memiliki satu atau banyak `FabricStock` (snapshot stok).

3. **Pembuatan Controller dan Views**
   - Buat `GarmentCategoryController` (CRUD dasar kategori pakaian).
   - Buat `FabricController` (CRUD Master Kain sekaligus mengelola snapshot kuantitasnya di `FabricStock`).
   - Buat halaman antarmuka agar Admin bisa mengontrol ketersediaan meter kain dan menambah kategori pakaian baru.

4. **Seeder Data Dummy**
   - Buat *Seeder* untuk kategori pakaian standar (contoh: Kemeja, Celana, Jas, Kebaya).
   - Buat *Seeder* untuk jenis kain (contoh: Katun, Linen, Drill) beserta snapshot awal stoknya.

## Kriteria Penerimaan
- Admin dapat menambah, mengubah, atau menghapus kategori pakaian yang akan jadi referensi ukuran.
- Admin dapat mendaftarkan bahan kain baru dan memantau stok (`quantity_in_meters`) yang tersisa.
- Ketersediaan master data ini terhubung lancar tanpa *error foreign key* saat pesanan atau ukuran pelanggan baru ditambahkan.
