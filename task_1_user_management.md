# Task 1: Manajemen Pengguna & Autentikasi (User Management)

## Deskripsi
Menyesuaikan sistem autentikasi dan manajemen pengguna (user management) yang sudah ada di Laravel agar selaras dengan PRD.md, khususnya terkait peran pengguna (user role). 

## Detail Pekerjaan
1. **Pembaruan Migrasi & Skema Tabel `users` dan `tailors`**
   - Pastikan tabel `users` memiliki kolom `role` dengan tipe enum/string ('Superadmin', 'Admin', 'Pegawai').
   - Buat migrasi tabel `tailors` (sebagai profil ekstensi pengguna) dengan kolom: `id`, `user_id` (FK), `specialization`, `is_available`, `created_at`, `updated_at`.
   - Ikuti konvensi penamaan dan struktur yang ada pada file migrasi saat ini (`0001_01_01_000000_create_users_table.php`).

2. **Model `User` dan `Tailor`**
   - Perbarui `$fillable` di `app/Models/User.php`.
   - Tambahkan helper method jika perlu (misal: `isAdmin()`, `isPegawai()`).
   - Buat model `app/Models/Tailor.php` dengan `$fillable` yang relevan, serta relasi `belongsTo(User::class)` dan `hasOne(Tailor::class)` di model `User`.

3. **Logika CRUD di `UserController`**
   - Sesuaikan fungsi Create, Read, Update, dan Delete di `app/Http/Controllers/UserController.php`.
   - Pastikan proses validasi form mengenali nilai role yang valid.
   - Saat membuat `User` dengan role Pegawai/Penjahit, secara otomatis buatkan record kosong atau form terkait di tabel `tailors`.
   - Sesuaikan view terkait (`resources/views/user/`).

4. **Seeder & Factory Data Dummy**
   - Sesuaikan `UserFactory.php` dan `UserSeeder.php` untuk mem-generate pengguna dummy.
   - Pastikan *seeder* juga menghasilkan data `Tailor` (mengisi `specialization` & `is_available`) untuk setiap pengguna ber-role Pegawai.
   - Buat minimal 5 data dummy pengguna.

## Standar & Konvensi
- Wajib mengikuti pola coding style, arsitektur MVC, dan konvensi penamaan yang sudah ada (existing) pada modul user saat ini.
- Dilarang membuat pola baru yang tidak konsisten.

## Kriteria Penerimaan
- Admin dapat menambah user baru dan menentukan rolenya.
- Data dummy berhasil di-seed dan dapat ditampilkan di tabel User Management.
- Login berjalan normal untuk setiap tipe role.
