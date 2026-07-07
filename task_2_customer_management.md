# Task 2: Manajemen Pelanggan (Customer Management)

## Deskripsi
Membangun fitur untuk mengelola data pelanggan, termasuk menyimpan profil dasar dan detail ukuran badan yang spesifik menggunakan kolom JSON.

## Detail Pekerjaan
1. **Pembuatan Migrasi `customers`**
   - Buat migrasi tabel `customers` yang memuat: `id`, `name`, `phone`, `address`, `measurements` (JSON), `created_at`, `updated_at`.
   - Jalankan `php artisan migrate`.

2. **Pembuatan Model `Customer`**
   - Tentukan atribut `$fillable`.
   - Gunakan casting untuk kolom `measurements` agar di-parse sebagai array di PHP (`protected $casts = ['measurements' => 'array'];`).

3. **Pembuatan `CustomerController` dan Views**
   - Buat fungsi CRUD lengkap (Index, Create, Store, Edit, Update, Destroy).
   - Buat form antarmuka untuk memasukkan input dinamis ukuran badan (seperti Panjang Lengan, Lingkar Dada, dll).
   - Gunakan layout dan komponen UI yang konsisten dengan template NiceAdmin yang sudah ada.

4. **Seeder Data Dummy**
   - Buat `CustomerFactory` dan `CustomerSeeder`.
   - Generate minimal 10 data pelanggan beserta data dummy ukuran badan.

## Kriteria Penerimaan
- Data pelanggan dapat ditambah, diedit, dihapus, dan dilihat.
- Penginputan ukuran badan berfungsi dengan baik dan tersimpan dalam format JSON.
- Data dummy berhasil di-seed.
