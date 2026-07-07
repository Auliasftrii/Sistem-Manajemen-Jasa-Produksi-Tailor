# Task 5: Manajemen Pembayaran (Payment & Invoicing)

## Deskripsi
Membangun modul pembayaran untuk mencatat Down Payment (DP) dan Pelunasan dari pesanan pelanggan.

## Detail Pekerjaan
1. **Pembuatan Migrasi `payments`**
   - Skema: `id`, `order_id`, `amount`, `payment_date`, `payment_method`, `status` (DP/Pelunasan), timestamps.

2. **Pembuatan Model `Payment`**
   - Atur relasi belongTo `Order`.

3. **Pembuatan `PaymentController` dan Views**
   - Form untuk menambahkan entri pembayaran pada suatu Order.
   - Validasi sisa tagihan (Total tagihan dikurangi total pembayaran sebelumnya).
   - Halaman cetak struk nota tagihan/pembayaran (Invoice).

4. **Seeder Data Dummy**
   - Buat seeder pembayaran dummy (contoh: 3 transaksi lunas, 2 transaksi baru DP).

## Kriteria Penerimaan
- Sistem tidak mengizinkan pembayaran melebih sisa tagihan.
- Histori pembayaran terekam dengan jelas pada detail pesanan.
- Nota / faktur bisa dicetak (Print Preview).
