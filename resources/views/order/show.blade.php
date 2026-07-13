<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <style>
        .invoice-card {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 40px;
        }
        .invoice-header-bg {
            background-color: #6B4E3D !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .bg-light-gray {
            background-color: #F8F9FA !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .total-box {
            background-color: #FFF8F5;
            border: 1px solid #EEDCD3;
            border-radius: 8px;
            padding: 20px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        @media print {
            .no-print { display: none !important; }
            .invoice-card { 
                box-shadow: none !important; 
                max-width: 100% !important; 
                padding: 0 !important; 
            }
            body { background: #fff !important; }
            .total-box { border: 2px solid #6B4E3D !important; }
            .sidebar, .header { display: none !important; }
            #main { margin: 0 !important; padding: 0 !important; }
            .page-header-card { display: none !important; }
        }
    </style>

    <div class="invoice-card mb-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
            <div>
                <h2 class="fw-bold mb-0" style="color: #6B4E3D;"><i class='bx bx-cut'></i> TailorPro</h2>
                <span class="text-muted small">Sistem Manajemen Jasa Produksi</span>
            </div>
            <div class="text-end">
                <h1 class="fw-bold text-uppercase mb-0" style="letter-spacing: 2px; color: #333;">Invoice</h1>
                <h4 class="text-muted">#{{ $order->invoice_number }}</h4>
                <div class="mt-2 d-flex align-items-center justify-content-end gap-2">
                    <span class="text-muted small">Tanggal: {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</span>
                    
                    @if($order->status == 'pending')
                        <span class="badge rounded-pill bg-warning text-dark">Pending</span>
                    @elseif($order->status == 'in_progress')
                        <span class="badge rounded-pill bg-primary">In Progress</span>
                    @elseif($order->status == 'completed')
                        <span class="badge rounded-pill bg-success">Completed</span>
                    @else
                        <span class="badge rounded-pill bg-danger">Cancelled</span>
                    @endif
                </div>

                @if($order->status == 'completed')
                    <div class="mt-3 no-print">
                        <a href="{{ route('revision.create', ['order_id' => $order->id]) }}" class="btn btn-warning btn-sm fw-bold shadow-sm">
                            <i class="bx bx-error-circle"></i> Buat Komplain
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Address Info -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="bg-light-gray p-3 rounded h-100">
                    <h6 class="text-uppercase text-muted fw-bold mb-2" style="font-size: 12px; letter-spacing: 1px;">Dari:</h6>
                    <h5 class="fw-bold mb-1">TailorPro Admin</h5>
                    <p class="mb-0 text-muted small">PIC: {{ $order->user->name ?? 'Sistem' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-light-gray p-3 rounded h-100">
                    <h6 class="text-uppercase text-muted fw-bold mb-2" style="font-size: 12px; letter-spacing: 1px;">Kepada:</h6>
                    <h5 class="fw-bold mb-1">{{ $order->customer->name ?? '-' }}</h5>
                    <p class="mb-0 text-muted small">
                        <i class="bx bx-phone"></i> {{ $order->customer->phone ?? '-' }}<br>
                        <i class="bx bx-map"></i> {{ $order->customer->address ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Info Boxes -->
        <div class="d-flex flex-column gap-2 mb-4">
            <!-- Expected Date Info Box -->
            <div class="d-flex align-items-center bg-light p-3 rounded border">
                <i class="bx bx-calendar-event fs-3 text-secondary me-3"></i>
                <div class="flex-grow-1">
                    <span class="d-block text-muted small fw-bold text-uppercase">Perkiraan Selesai</span>
                    <span class="fs-6 fw-bold">
                        {{ $order->expected_completion_date ? \Carbon\Carbon::parse($order->expected_completion_date)->format('d M Y') : 'Belum ditentukan' }}
                    </span>
                </div>
                <!-- Inline Edit Form (No Print) -->
                <div class="no-print">
                    <form action="{{ route('order.update', $order) }}" method="post" class="d-flex align-items-center gap-2 m-0">
                        @csrf
                        @method('put')
                        <!-- Hidden status to pass validation -->
                        <input type="hidden" name="status" value="{{ $order->status }}">
                        <input type="date" name="expected_completion_date" class="form-control form-control-sm" value="{{ $order->expected_completion_date ? \Carbon\Carbon::parse($order->expected_completion_date)->format('Y-m-d') : '' }}">
                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Update Tanggal"><i class="bx bx-check"></i></button>
                    </form>
                </div>
            </div>

            <!-- Revisions Alert -->
            @if($order->revisions->count() > 0)
                @php
                    $unresolved = $order->revisions->where('status', '!=', 'Resolved')->count();
                @endphp
                @if($unresolved > 0)
                    <div class="d-flex align-items-center alert alert-warning p-3 m-0 rounded border border-warning">
                        <i class="bx bx-error fs-3 me-3"></i>
                        <div>
                            <span class="d-block text-dark small fw-bold text-uppercase">Status Komplain</span>
                            <span class="fs-6">Terdapat <strong>{{ $unresolved }}</strong> komplain yang sedang diproses.</span>
                        </div>
                    </div>
                @else
                    <div class="d-flex align-items-center alert alert-success p-3 m-0 rounded border border-success">
                        <i class="bx bx-check-circle fs-3 me-3"></i>
                        <div>
                            <span class="d-block text-dark small fw-bold text-uppercase">Status Komplain</span>
                            <span class="fs-6">Semua komplain/revisi telah diselesaikan.</span>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- Items Table -->
        <div class="table-responsive mb-4">
            <table class="table table-striped table-hover border align-middle">
                <thead class="invoice-header-bg">
                    <tr>
                        <th class="py-3 px-3">No</th>
                        <th class="py-3">Product Type</th>
                        <th class="py-3">Fabric Details</th>
                        <th class="py-3 text-end">Qty</th>
                        <th class="py-3 text-end px-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($order->items as $item)
                        <tr>
                            <td class="px-3">{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $item->product_type }}</td>
                            <td class="text-muted">{{ $item->fabric_details ?? '-' }}</td>
                            <td class="text-end">{{ $item->quantity }}</td>
                            <td class="text-end px-3">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada item pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Total & Notes -->
        <div class="row align-items-end">
            <div class="col-md-6 mb-3 mb-md-0">
                <p class="text-muted small fst-italic mb-0">
                    "Terima kasih telah mempercayakan jahitan Anda pada TailorPro. Kualitas dan kepuasan Anda adalah prioritas utama kami."
                </p>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end">
                <div class="total-box text-end" style="min-width: 250px;">
                    <span class="text-uppercase text-muted fw-bold small" style="letter-spacing: 1px;">Total Keseluruhan</span>
                    <h2 class="fw-bold mb-0 mt-1" style="color: #6B4E3D;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>

        <!-- Action Buttons (No Print) -->
        <div class="d-flex gap-2 justify-content-end mt-5 pt-3 border-top no-print">
            <a href="{{ route('order.index') }}" class="btn btn-light border shadow-sm">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-secondary shadow-sm">
                <i class="bx bx-printer"></i> Cetak / Download PDF
            </button>
        </div>
    </div>
</x-app>
