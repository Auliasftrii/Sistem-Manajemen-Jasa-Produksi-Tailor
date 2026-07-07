<div class="invoice p-3 mb-3">
    <!-- title row -->
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="bx bx-receipt"></i> INVOICE
                <small class="float-end">Tanggal: {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</small>
            </h4>
        </div>
        <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info mt-3">
        <div class="col-sm-4 invoice-col">
            Dari
            <address>
                <strong>Tailor Admin</strong><br>
                User: {{ $order->user->name ?? 'Sistem' }}<br>
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            Kepada
            <address>
                <strong>{{ $order->customer->name ?? '-' }}</strong><br>
                Phone: {{ $order->customer->phone ?? '-' }}<br>
                Alamat: {{ $order->customer->address ?? '-' }}
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <b>Invoice #{{ $order->invoice_number }}</b><br>
            <br>
            <b>Status:</b> 
            @if($order->status == 'pending')
                <span class="badge bg-warning text-dark">Pending</span>
            @elseif($order->status == 'in_progress')
                <span class="badge bg-primary">In Progress</span>
            @elseif($order->status == 'completed')
                <span class="badge bg-success">Completed</span>
            @else
                <span class="badge bg-danger">Cancelled</span>
            @endif
            <br>
            <b>Perkiraan Selesai:</b> {{ $order->expected_completion_date ? \Carbon\Carbon::parse($order->expected_completion_date)->format('d/m/Y') : '-' }}
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row mt-4">
        <div class="col-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Product Type</th>
                        <th>Fabric Details</th>
                        <th>Qty</th>
                        <th>Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->product_type }}</td>
                            <td>{{ $item->fabric_details ?? '-' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- accepted payments column -->
        <div class="col-6">
            <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                Terima kasih telah menggunakan jasa kami. Kepuasan Anda adalah prioritas kami.
            </p>
        </div>
        <!-- /.col -->
        <div class="col-6">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">Total:</th>
                        <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
