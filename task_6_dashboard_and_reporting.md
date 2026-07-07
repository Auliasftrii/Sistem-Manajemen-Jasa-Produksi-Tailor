# Task 6: Dashboard & Laporan (Dashboard and Reporting)

## Deskripsi
Membangun halaman beranda (dashboard) dan fitur laporan yang menampilkan rangkuman data bisnis secara garis besar.

## Detail Pekerjaan
1. **Pembaruan `DashboardController` dan Views**
   - Tampilkan metrik ringkasan pada kotak-kotak dashboard (Total Pesanan, Pendapatan Bulan Ini, Jumlah Pelanggan, Pesanan Belum Selesai).
   - Gunakan komponen chart/grafik dari NiceAdmin (ApexCharts/Chart.js) untuk visualisasi pendapatan.

2. **Pembuatan Modul Laporan (Reporting)**
   - Fitur filter laporan berdasarkan rentang tanggal.
   - Menampilkan tabel pesanan selesai dan total pendapatan pada periode tertentu.

3. **Keterkaitan Data Dummy**
   - Memastikan data-data dummy dari seluruh seeder (Users, Customers, Orders, Payments) dapat merender chart dan metrik pada dashboard.

## Kriteria Penerimaan
- Dashboard menampilkan data aktual sesuai perhitungan tabel di database.
- Laporan dapat di-filter dan bekerja dengan lancar.
