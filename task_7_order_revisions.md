# Task 7: Revisi Pesanan (Order Revisions)

## Deskripsi
Membangun fitur untuk mencatat dan mengelola komplain atau permintaan revisi jahitan dari pelanggan setelah pesanan (orders) diselesaikan.

## Detail Pekerjaan
1. **Pembuatan Migrasi `order_revisions`**
   - Buat migrasi tabel `order_revisions` yang memuat: `id`, `order_id`, `revision_notes` (text), `status` (string: Pending, In Progress, Resolved), `reported_at`, `resolved_at`, `created_at`, `updated_at`.
   - Pastikan relasi *foreign key* `order_id` merujuk ke tabel `orders`.
   - Jalankan `php artisan migrate`.

2. **Pembuatan Model `OrderRevision`**
   - Tentukan atribut `$fillable`.
   - Atur relasi: `belongsTo(Order::class)`.

3. **Pembuatan `OrderRevisionController` dan Views**
   - Buat antarmuka CRUD untuk revisi pesanan.
   - Buat halaman daftar revisi yang memungkinkan admin untuk mengubah status revisi (contoh: dari *Pending* ke *Resolved* beserta pencatatan *resolved_at*).
   - Pastikan notifikasi/indikator visual ditambahkan pada detail pesanan (`orders`) apabila pesanan tersebut memiliki revisi aktif.

4. **Seeder Data Dummy**
   - Buat `OrderRevisionFactory` dan `OrderRevisionSeeder`.
   - Hasilkan beberapa data sampel keluhan untuk pesanan yang sudah *completed*.

## Kriteria Penerimaan
- Admin dapat mendaftarkan keluhan perbaikan jahitan pelanggan yang terkait dengan nomor *invoice* tertentu.
- Perubahan status penyelesaian komplain (Resolved) dapat dipantau.
- Riwayat revisi terikat dengan benar ke tabel `orders`.
