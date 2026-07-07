<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-lg p-3">
                <form action="{{ route('payment.create') }}" method="get" class="mb-4">
                    <label for="order_id_select" class="form-label fw-bold">Pilih Pesanan yang Akan Dibayar</label>
                    <div class="input-group">
                        <select class="form-select select2-default" id="order_id_select" name="order_id" required>
                            <option value="">-- Pilih Pesanan --</option>
                            @foreach($orders as $orderItem)
                                <option value="{{ $orderItem->id }}" @selected(request('order_id') == $orderItem->id)>
                                    {{ $orderItem->invoice_number }} - {{ $orderItem->customer->name ?? '' }} (Total: Rp {{ number_format($orderItem->total_amount, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary" type="submit">Cek Detail</button>
                    </div>
                </form>

                @if($selectedOrder)
                    <h5 class="fw-bold border-bottom pb-2">Informasi Pembayaran</h5>
                    <table class="table table-sm mt-3">
                        <tr>
                            <th>Invoice</th>
                            <td>: {{ $selectedOrder->invoice_number }}</td>
                        </tr>
                        <tr>
                            <th>Pelanggan</th>
                            <td>: {{ $selectedOrder->customer->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Total Tagihan</th>
                            <td>: <strong>Rp {{ number_format($selectedOrder->total_amount, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <th>Telah Dibayar</th>
                            <td>: Rp {{ number_format($selectedOrder->payments->sum('amount'), 0, ',', '.') }}</td>
                        </tr>
                        <tr class="table-warning">
                            <th>Sisa Tagihan</th>
                            <td>: <strong class="text-danger">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>

                    @if($sisaTagihan > 0)
                        <form action="{{ route('payment.store') }}" method="post" class="mt-4 border-top pt-3">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $selectedOrder->id }}">

                            <div class="mb-3">
                                <label for="amount" class="form-label required">Nominal Bayar (Rp)</label>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $sisaTagihan) }}" max="{{ $sisaTagihan }}" min="1" required>
                                <div class="form-text">Maksimal pembayaran adalah Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="payment_date" class="form-label required">Tanggal Bayar</label>
                                    <input type="date" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                    @error('payment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="payment_method" class="form-label required">Metode</label>
                                    <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                        <option value="Cash" @selected(old('payment_method') == 'Cash')>Cash / Tunai</option>
                                        <option value="Transfer Bank" @selected(old('payment_method') == 'Transfer Bank')>Transfer Bank</option>
                                        <option value="E-Wallet" @selected(old('payment_method') == 'E-Wallet')>E-Wallet (OVO/Dana/Gopay)</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label required">Status (DP/Lunas)</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="DP" @selected(old('status') == 'DP')>Down Payment (DP)</option>
                                    <option value="Pelunasan" @selected(old('status') == 'Pelunasan')>Pelunasan</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success">Simpan Pembayaran</button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-success mt-4">
                            <i class='bx bx-check-circle'></i> Pesanan ini sudah <strong>LUNAS</strong>. Tidak ada sisa tagihan.
                        </div>
                    @endif
                @else
                    <div class="alert alert-info mt-3">Silakan pilih pesanan terlebih dahulu untuk melihat detail tagihan dan form input pembayaran.</div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-lg p-3">
                <h5 class="fw-bold mb-3">Histori Pembayaran</h5>
                @if($selectedOrder && $selectedOrder->payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nominal</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedOrder->payments as $payment)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        <td>{{ $payment->payment_method }}</td>
                                        <td>{{ $payment->status }}</td>
                                        <td>
                                            <form action="{{ route('payment.destroy', $payment) }}" method="post" onsubmit="return confirm('Yakin ingin membatalkan pembayaran ini? Sisa tagihan akan kembali bertambah.')">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-danger btn-sm" type="submit"><i class="bx bx-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-muted text-center py-4">Belum ada riwayat pembayaran dicatat.</div>
                @endif
            </div>
        </div>
    </div>

</x-app>
