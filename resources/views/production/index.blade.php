<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Invoice</th>
                        <th scope="col">Pelanggan</th>
                        <th scope="col">Status Pesanan</th>
                        <th scope="col">Progress Produksi</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="fw-bold">{{ $order->invoice_number }}</span></td>
                            <td>{{ $order->customer->name ?? '-' }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($order->status == 'in_progress')
                                    <span class="badge bg-primary">In Progress</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $completedCount = $order->trackings->where('status', 'completed')->count();
                                    $totalStages = \App\Models\ProductionStage::count();
                                    $totalStages = $totalStages > 0 ? $totalStages : 1; // avoid div by zero
                                    $percentage = ($completedCount / $totalStages) * 100;
                                @endphp
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar {{ $percentage == 100 ? 'bg-success' : 'bg-info' }}" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">{{ $percentage }}%</div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('production.edit', $order) }}" class="btn btn-primary btn-sm">
                                    <i class='bx bx-edit-alt'></i> Update Progress
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-app>
