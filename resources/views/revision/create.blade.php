<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">
        <form action="{{ route('revision.store') }}" method="post">
            @csrf

            <div class="mb-3">
                <label for="order_id" class="form-label fw-bold">Pilih Pesanan (Invoice) *</label>
                <select name="order_id" id="order_id" class="form-select select2" required>
                    <option value="" disabled selected>-- Pilih Pesanan --</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" @selected(old('order_id', $selectedOrder?->id) == $order->id)>
                            {{ $order->invoice_number }} - {{ $order->customer->name ?? '-' }} ({{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }})
                        </option>
                    @endforeach
                </select>
                <div class="form-text text-muted">Hanya pesanan yang sudah berstatus Completed yang dapat dikomplain/direvisi.</div>
                @error('order_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="revision_notes" class="form-label fw-bold">Detail Keluhan / Catatan Revisi *</label>
                <textarea name="revision_notes" id="revision_notes" rows="4" class="form-control" required placeholder="Contoh: Jahitan lengan kiri kurang rapi, ukuran terlalu sempit di bagian pinggang...">{{ old('revision_notes') }}</textarea>
                @error('revision_notes')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-end">
                <a href="{{ route('revision.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Komplain</button>
            </div>
        </form>
    </div>
</x-app>
