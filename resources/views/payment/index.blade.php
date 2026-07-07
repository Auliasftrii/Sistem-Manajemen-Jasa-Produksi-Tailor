<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('payment.create') }}" role="button">Catat Pembayaran Baru</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Invoice Order</th>
                        <th scope="col">Pelanggan</th>
                        <th scope="col">Total Tagihan</th>
                        <th scope="col">Telah Dibayar</th>
                        <th scope="col">Sisa Tagihan</th>
                        <th scope="col">Status Lunas</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        @php
                            $totalBayar = $order->payments->sum('amount');
                            $sisa = $order->total_amount - $totalBayar;
                            $isLunas = $sisa <= 0 && $order->total_amount > 0;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="fw-bold">{{ $order->invoice_number }}</span></td>
                            <td>{{ $order->customer->name ?? '-' }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="text-success">Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
                            <td class="text-danger">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                            <td>
                                @if($isLunas)
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Lunas</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('payment.create', ['order_id' => $order->id]) }}" class="btn btn-primary btn-sm mb-1" title="Tambah Bayar">
                                    <i class='bx bx-plus-circle'></i> Bayar
                                </a>
                                <a href="{{ route('payment.print', $order) }}" class="btn btn-secondary btn-sm mb-1" target="_blank" title="Cetak Nota">
                                    <i class='bx bx-printer'></i> Cetak
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-app>
