# Task 3: Manajemen Pesanan (Order Management)

## Deskripsi
Membangun fitur manajemen pesanan untuk mencatat transaksi pelanggan baru beserta item-item pakaian yang akan diproduksi.

## Detail Pekerjaan
1. **Pembuatan Migrasi `orders` & `order_items`**
   - Skema `orders`: `id`, `customer_id`, `user_id`, `invoice_number`, `order_date`, `expected_completion_date`, `total_amount`, `status`, timestamps.
   - Skema `order_items`: `id`, `order_id`, `product_type`, `fabric_details`, `quantity`, `unit_price`, `subtotal`, timestamps.
   - Tetapkan foreign keys.

2. **Pembuatan Model `Order` dan `OrderItem`**
   - Atur relasi: `Order` belongTo `Customer`, `Order` belongTo `User`, `Order` hasMany `OrderItem`.

3. **Pembuatan `OrderController` dan Views**
   - Buat fungsi pembuatan pesanan dengan kemampuan multiple items (Master-Detail form).
   - Implementasi auto-generate `invoice_number` berdasarkan tanggal dan urutan.

4. **Seeder Data Dummy**
   - Buat `OrderFactory` dan `OrderItemFactory`.
   - Seed pesanan fiktif (minimal 5 pesanan dengan item acak).

## Kriteria Penerimaan
- Mampu membuat pesanan baru dan memasukkan lebih dari satu item pakaian sekaligus.
- Penjumlahan otomatis subtotal ke `total_amount` berjalan dengan benar.
- Relasi antara data pelanggan, admin (user), dan pesanan bekerja dengan baik.
