# Task 4: Pelacakan Produksi (Production Tracking)

## Deskripsi
Membangun fitur pelacakan tahapan produksi untuk setiap pesanan pakaian, mencatat status proses serta pihak penjahit yang bertanggung jawab.

## Detail Pekerjaan
1. **Pembuatan Migrasi `production_stages` dan `production_trackings`**
   - Skema `production_stages`: `id`, `stage_name`, `sequence_order`, `created_at`, `updated_at`.
   - Skema `production_trackings`: `id`, `order_id`, `production_stage_id`, `status`, `tailor_id` (penjahit), `started_at`, `completed_at`.

2. **Pembuatan Model `ProductionStage` dan `ProductionTracking`**
   - Tentukan atribut `$fillable` untuk `ProductionStage`.
   - Atur relasi di `ProductionTracking`: `belongsTo` `Order`, `ProductionStage`, dan `Tailor`.

3. **Pembuatan `ProductionTrackingController` dan Views**
   - Halaman Kanban / List status produksi berdasarkan pesanan (menggunakan rentang urutan `sequence_order` dari `production_stages`).
   - Fungsionalitas untuk meng-update status (dari Pending ke Proses, lalu Selesai).
   - Pencatatan log waktu `started_at` dan `completed_at` secara otomatis saat status diubah.

4. **Seeder Data Dummy**
   - Buat `ProductionStageSeeder` untuk menyemai tahapan dasar (misal: Potong, Jahit, Finishing).
   - Generate riwayat tracking dummy untuk sebagian pesanan.

## Kriteria Penerimaan
- Pengguna dengan role Pegawai dapat mengubah status pesanan yang sedang dikerjakan.
- Log tahapan beserta waktu pencatatan tersimpan di database.
