# Task 4: Pelacakan Produksi (Production Tracking)

## Deskripsi
Membangun fitur pelacakan tahapan produksi untuk setiap pesanan pakaian, mencatat status proses serta pihak penjahit yang bertanggung jawab.

## Detail Pekerjaan
1. **Pembuatan Migrasi `production_trackings`**
   - Skema: `id`, `order_id`, `stage`, `status`, `handled_by` (user_id penjahit), `started_at`, `completed_at`.

2. **Pembuatan Model `ProductionTracking`**
   - Atur relasi: belongTo `Order`, belongTo `User` (sebagai penjahit).

3. **Pembuatan `ProductionTrackingController` dan Views**
   - Halaman Kanban / List status produksi berdasarkan pesanan (Pending, Cutting, Sewing, Finishing).
   - Fungsionalitas untuk meng-update status (dari Pending ke Proses, lalu Selesai).
   - Pencatatan log waktu `started_at` dan `completed_at` secara otomatis saat status diubah.

4. **Seeder Data Dummy**
   - Generate riwayat tracking dummy untuk sebagian pesanan (Seeder).

## Kriteria Penerimaan
- Pengguna dengan role Pegawai dapat mengubah status pesanan yang sedang dikerjakan.
- Log tahapan beserta waktu pencatatan tersimpan di database.
