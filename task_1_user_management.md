# Task 1: Manajemen Pengguna & Autentikasi (User Management)

## Deskripsi
Menyesuaikan sistem autentikasi dan manajemen pengguna (user management) yang sudah ada di Laravel agar selaras dengan PRD.md, khususnya terkait peran pengguna (user role). 

## Detail Pekerjaan
1. **Pembaruan Migrasi & Skema Tabel `users`**
   - Pastikan tabel `users` memiliki kolom `role` dengan tipe enum/string ('Superadmin', 'Admin', 'Pegawai').
   - Ikuti konvensi penamaan dan struktur yang ada pada file migrasi saat ini (`0001_01_01_000000_create_users_table.php`).

2. **Model `User`**
   - Perbarui `$fillable` di `app/Models/User.php` untuk menyertakan atribut yang diperlukan sesuai skema baru.
   - Tambahkan helper method jika perlu (misal: `isAdmin()`, `isPegawai()`).

3. **Logika CRUD di `UserController`**
   - Sesuaikan fungsi Create, Read, Update, dan Delete di `app/Http/Controllers/UserController.php`.
   - Pastikan proses validasi form mengenali nilai role yang valid.
   - Sesuaikan view terkait (`resources/views/user/`) untuk menampilkan dan memproses form input role.

4. **Seeder & Factory Data Dummy**
   - Sesuaikan `UserFactory.php` dan `UserSeeder.php` untuk mem-generate pengguna dummy berdasarkan 3 peran tersebut (Superadmin, Admin, Pegawai).
   - Buat minimal 5 data dummy pengguna untuk visualisasi.

## Standar & Konvensi
- Wajib mengikuti pola coding style, arsitektur MVC, dan konvensi penamaan yang sudah ada (existing) pada modul user saat ini.
- Dilarang membuat pola baru yang tidak konsisten.

## Kriteria Penerimaan
- Admin dapat menambah user baru dan menentukan rolenya.
- Data dummy berhasil di-seed dan dapat ditampilkan di tabel User Management.
- Login berjalan normal untuk setiap tipe role.
