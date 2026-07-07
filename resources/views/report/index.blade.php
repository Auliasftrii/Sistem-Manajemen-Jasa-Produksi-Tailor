<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3 mb-4">
        <form action="{{ route('report.index') }}" method="get" class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="start_date" class="col-form-label fw-bold">Dari Tanggal:</label>
            </div>
            <div class="col-auto">
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate }}" required>
            </div>
            <div class="col-auto">
                <label for="end_date" class="col-form-label fw-bold">Sampai:</label>
            </div>
            <div class="col-auto">
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate }}" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary"><i class="bx bx-filter"></i> Filter</button>
                <a href="{{ route('report.index') }}" class="btn btn-secondary"><i class="bx bx-reset"></i> Reset</a>
            </div>
        </form>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title text-white"><i class="bx bx-check-square"></i> Omzet Pesanan Selesai</h6>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                    <small>Total nilai dari {{ $orders->count() }} pesanan yang berstatus Completed pada periode ini.</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-info text-white shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title text-white"><i class="bx bx-wallet"></i> Uang Riil Diterima (Cash Flow)</h6>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($pendapatanRiil, 0, ',', '.') }}</h3>
                    <small>Total nominal pembayaran (DP & Pelunasan) yang tercatat masuk pada rentang waktu ini.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-lg p-3">
        <h5 class="fw-bold border-bottom pb-2 mb-3">Rincian Pesanan Selesai ({{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }})</h5>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Tgl Pesan</th>
                        <th>Tgl Selesai (Est)</th>
                        <th>Total Nilai (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="fw-bold">{{ $order->invoice_number }}</span></td>
                            <td>{{ $order->customer->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                            <td>{{ $order->expected_completion_date ? \Carbon\Carbon::parse($order->expected_completion_date)->format('d/m/Y') : '-' }}</td>
                            <td class="text-end fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada data pesanan selesai pada periode yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app>
