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
                        <th scope="col">Tahapan</th>
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
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.35rem;">
                                    @foreach($order->trackings->sortBy(function($t) { return $t->productionStage->sequence_order ?? 0; }) as $tracking)
                                        @php
                                            $stageName = $tracking->productionStage->stage_name ?? 'Tahap';
                                            $status = $tracking->status;
                                            
                                            $bgColor = 'bg-secondary';
                                            $icon = 'bx bx-time';
                                            $statusLabel = 'Pending';
                                            
                                            if ($status == 'in_progress') {
                                                $bgColor = 'bg-info text-dark'; // bg-info can sometimes have poor contrast with white text, using text-dark to ensure readability
                                                $icon = 'bx bx-loader-alt bx-spin';
                                                $statusLabel = 'In Progress';
                                            } elseif ($status == 'completed') {
                                                $bgColor = 'bg-success';
                                                $icon = 'bx bx-check';
                                                $statusLabel = 'Completed';
                                            }

                                            $tailorName = $tracking->tailor ? $tracking->tailor->user->name : 'Belum ditentukan';
                                            $start = $tracking->started_at ? \Carbon\Carbon::parse($tracking->started_at)->format('d/m/Y') : '-';
                                            $end = $tracking->completed_at ? \Carbon\Carbon::parse($tracking->completed_at)->format('d/m/Y') : '-';
                                            $tooltip = "Pegawai: $tailorName | Mulai: $start | Selesai: $end";
                                            
                                            $isMyTask = Auth::check() && Auth::user()->tailor && $tracking->tailor_id == Auth::user()->tailor->id;
                                            $myTaskClass = $isMyTask ? 'border border-2 border-dark shadow' : '';
                                        @endphp
                                        <div class="badge rounded-pill {{ $bgColor }} {{ $myTaskClass }} d-flex flex-column align-items-center justify-content-center px-2 py-1" title="{{ $tooltip }}" style="cursor: help;">
                                            <span class="fw-bold" style="font-size: 0.75rem;">
                                                @if($isMyTask) <i class='bx bxs-user-pin' title="Tugas Anda"></i> @endif
                                                {{ $stageName }}
                                            </span>
                                            <span class="fw-normal" style="font-size: 0.65rem; margin-top: 2px;"><i class="{{ $icon }}"></i> {{ $statusLabel }}</span>
                                        </div>
                                    @endforeach
                                    @if($order->trackings->isEmpty())
                                        <span class="text-muted small" style="grid-column: span 2;">Belum ada tahapan</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $completedCount = $order->trackings->where('status', 'completed')->count();
                                    $totalStages = \App\Models\ProductionStage::count();
                                    $totalStages = $totalStages > 0 ? $totalStages : 1; // avoid div by zero
                                    $percentage = ($completedCount / $totalStages) * 100;
                                @endphp
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar {{ $percentage == 100 ? 'bg-success' : 'bg-info' }}" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">{{ round($percentage) }}%</div>
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
