<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">

        <form action="{{ route('order.update', $order) }}" method="post" class="form">
            @csrf
            @method('put')

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="mb-3">Informasi Pesanan</h5>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="30%"><strong>Invoice</strong></td>
                            <td>: {{ $order->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Pelanggan</strong></td>
                            <td>: {{ $order->customer->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Pesan</strong></td>
                            <td>: {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Amount</strong></td>
                            <td>: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="mb-3">Update Status</h5>
                    
                    <div class="mb-3">
                        <label for="expected_completion_date" class="form-label">Perkiraan Selesai</label>
                        <input type="date" class="form-control @error('expected_completion_date') is-invalid @enderror" id="expected_completion_date" name="expected_completion_date" value="{{ old('expected_completion_date', $order->expected_completion_date) }}">
                        @error('expected_completion_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label required">Status Pesanan</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="pending" @selected(old('status', $order->status) == 'pending')>Pending</option>
                            <option value="in_progress" @selected(old('status', $order->status) == 'in_progress')>In Progress</option>
                            <option value="completed" @selected(old('status', $order->status) == 'completed')>Completed</option>
                            <option value="cancelled" @selected(old('status', $order->status) == 'cancelled')>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <h5 class="mb-3 mt-4">Rincian Item (Hanya Tampil)</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Jenis Pakaian</th>
                            <th>Detail Kain</th>
                            <th>Qty</th>
                            <th>Harga Satuan (Rp)</th>
                            <th>Subtotal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_type }}</td>
                                <td>{{ $item->fabric_details ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('order.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>

        </form>

    </div>

</x-app>
