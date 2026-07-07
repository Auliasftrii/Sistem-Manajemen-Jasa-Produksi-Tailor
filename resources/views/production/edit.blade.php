<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-lg p-3 mb-4">
                <h5 class="fw-bold mb-3">Info Pesanan</h5>
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Invoice</th>
                        <td>: {{ $order->invoice_number }}</td>
                    </tr>
                    <tr>
                        <th>Pelanggan</th>
                        <td>: {{ $order->customer->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tgl Pesan</th>
                        <td>: {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Deadline</th>
                        <td>: {{ $order->expected_completion_date ? \Carbon\Carbon::parse($order->expected_completion_date)->format('d M Y') : '-' }}</td>
                    </tr>
                </table>
            </div>

            <div class="card shadow-lg p-3">
                <h5 class="fw-bold mb-3">Daftar Pakaian</h5>
                <ul class="list-group list-group-flush">
                    @foreach($order->items as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            {{ $item->product_type }} ({{ $item->fabric_details ?? '-' }})
                            <span class="badge bg-primary rounded-pill">{{ $item->quantity }} pcs</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-lg p-3">
                <h5 class="fw-bold mb-4">Kanban Progress Produksi</h5>

                <form action="{{ route('production.update', $order) }}" method="post">
                    @csrf
                    @method('put')

                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        @foreach(['Pola', 'Potong', 'Jahit', 'Finishing'] as $stage)
                            @php
                                $tracking = $order->trackings->where('stage', $stage)->first();
                            @endphp
                            @if($tracking)
                                <div class="col">
                                    <div class="card h-100 border-{{ $tracking->status == 'completed' ? 'success' : ($tracking->status == 'in_progress' ? 'primary' : 'warning') }}">
                                        <div class="card-header fw-bold bg-light">
                                            Tahap: {{ $stage }}
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" name="trackings[{{ $loop->index }}][id]" value="{{ $tracking->id }}">
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Status Pekerjaan</label>
                                                <select class="form-select" name="trackings[{{ $loop->index }}][status]">
                                                    <option value="pending" @selected($tracking->status == 'pending')>Pending (Menunggu)</option>
                                                    <option value="in_progress" @selected($tracking->status == 'in_progress')>In Progress (Dikerjakan)</option>
                                                    <option value="completed" @selected($tracking->status == 'completed')>Completed (Selesai)</option>
                                                </select>
                                            </div>

                                            <div class="small text-muted mt-3">
                                                <div><strong>Dikerjakan oleh:</strong> {{ $tracking->handler->name ?? '-' }}</div>
                                                <div><strong>Mulai:</strong> {{ $tracking->started_at ? \Carbon\Carbon::parse($tracking->started_at)->format('d/m/Y H:i') : '-' }}</div>
                                                <div><strong>Selesai:</strong> {{ $tracking->completed_at ? \Carbon\Carbon::parse($tracking->completed_at)->format('d/m/Y H:i') : '-' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('production.index') }}" class="btn btn-secondary me-2">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan Progress</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app>
