# Task 2: Manajemen Pelanggan (Customer Management)

## Deskripsi
Membangun fitur untuk mengelola data pelanggan, termasuk menyimpan profil dasar dan detail ukuran badan yang spesifik menggunakan kolom JSON.

## Detail Pekerjaan
1. **Pembuatan Migrasi `customers` dan `customer_measurements`**
   - Buat migrasi tabel `customers` yang memuat: `id`, `name`, `phone`, `address`, `created_at`, `updated_at`.
   - Buat migrasi tabel `customer_measurements` dengan kolom: `id`, `customer_id` (FK), `garment_category_id` (FK), `measurement_key`, `measurement_value`, `created_at`, `updated_at`.
   - Jalankan `php artisan migrate`.

2. **Pembuatan Model `Customer` dan `CustomerMeasurement`**
   - Tentukan atribut `$fillable` untuk kedua model tersebut.
   - Atur relasi `hasMany(CustomerMeasurement::class)` di model `Customer` dan `belongsTo` di `CustomerMeasurement`.

3. **Pembuatan `CustomerController` dan Views**
   - Buat fungsi CRUD lengkap untuk pelanggan.
   - Di dalam halaman *Detail* atau *Edit* Pelanggan, sediakan antarmuka khusus (sub-form) untuk menambahkan/memperbarui ukuran (`customer_measurements`) berdasarkan `garment_category_id`.
   - Gunakan layout dan komponen UI yang konsisten dengan template NiceAdmin yang sudah ada.

4. **Seeder Data Dummy**
   - Buat `CustomerFactory`, `CustomerSeeder`, `CustomerMeasurementFactory`, dan `CustomerMeasurementSeeder`.
   - Generate minimal 10 data pelanggan.
   - Hasilkan juga data ukuran dummy untuk beberapa pelanggan yang terikat ke ID kategori pakaian tertentu.

## Kriteria Penerimaan
- Data pelanggan dapat ditambah, diedit, dihapus, dan dilihat.
- Penginputan ukuran badan terstruktur dengan baik ke dalam tabel `customer_measurements` dan merujuk ke tabel referensi.
- Data dummy berhasil di-seed.
