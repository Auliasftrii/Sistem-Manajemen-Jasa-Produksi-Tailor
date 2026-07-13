<x-guest>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-6" style="max-width: 700px;">
                <!-- Search Form Card -->
                <div class="card soft-card mb-4">
                    <div class="card-body p-4">
                        <div class="text-center mb-5">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 80px; height: 80px;">
                                <i class='bx bx-search-alt fs-1'></i>
                            </div>
                            <h2 class="fw-bolder mb-3 text-dark text-nowrap d-none d-sm-block">Lacak Pesanan</h2>
                            <h2 class="fw-bolder mb-3 text-dark d-block d-sm-none">Lacak Pesanan</h2>
                            <p class="text-muted fs-6 px-3 mx-auto" style="max-width: 450px; line-height: 1.6;">Masukkan Nomor Invoice untuk melihat status dan progres pesanan Anda secara real-time.</p>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger rounded-4 d-flex align-items-center shadow-sm border-0 mb-4 py-3">
                                <i class='bx bx-error-circle fs-4 me-2'></i>
                                <div>{{ session('error') }}</div>
                            </div>
                        @endif

                        <form action="{{ route('tracking.search') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="invoice_number" class="form-label fw-bold text-dark">Nomor Invoice</label>
                                <div class="input-group input-group-lg shadow-sm rounded-4 overflow-hidden border">
                                    <span class="input-group-text bg-white border-0 text-muted px-3 pe-2">
                                        <i class='bx bx-receipt fs-4'></i>
                                    </span>
                                    <input type="text" class="form-control border-0 px-2 fw-bold" id="invoice_number" name="invoice_number" 
                                        value="{{ old('invoice_number', $order ? $order->invoice_number : '') }}" 
                                        placeholder="Contoh: INV-20231015-0001" required>
                                </div>
                            </div>
                            <div class="d-grid gap-3 mt-5">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #8B5CF6, #6D28D9); border: none;">
                                    <i class='bx bx-search-alt me-2 fs-5'></i> Temukan Pesanan
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-light btn-lg rounded-pill fw-bold text-muted hover-shadow transition">
                                    <i class='bx bx-home-alt me-2'></i> Kembali ke Beranda
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($order))
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8" style="max-width: 800px;">
                
                @php
                    $hasUnresolvedRevision = $order->revisions->where('status', '!=', 'Resolved')->count() > 0;
                @endphp
                
                @if($hasUnresolvedRevision)
                    <div class="alert alert-warning rounded-3 shadow-sm mb-4">
                        <i class='bx bx-error-circle'></i> Pesanan ini sedang dalam tahap <strong>Revisi</strong> dan sedang kami proses.
                    </div>
                @endif

                <!-- Order Info Card -->
                <div class="card soft-card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                    <div class="card-body p-5">
                        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-start align-items-md-center mb-4 border-bottom pb-4 gap-3">
                            <div class="min-w-0">
                                <p class="text-muted text-uppercase tracking-wider fw-bold mb-1" style="font-size: 0.8rem; letter-spacing: 1px;">Data Pesanan</p>
                                <h3 class="fw-bolder text-dark mb-1 text-break">{{ $order->invoice_number }}</h3>
                                <div class="d-flex align-items-center text-muted flex-wrap">
                                    <i class='bx bx-user-circle fs-5 me-1 flex-shrink-0'></i> 
                                    <span class="fs-6 text-truncate">{{ $order->customer->name }}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                @php
                                    $badgeClass = 'bg-secondary bg-opacity-10 text-secondary';
                                    if ($order->status == 'completed') $badgeClass = 'bg-success bg-opacity-10 text-success border border-success border-opacity-25';
                                    elseif ($order->status == 'in_progress') $badgeClass = 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25';
                                    elseif ($order->status == 'revisi') $badgeClass = 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25';
                                    elseif ($order->status == 'cancelled') $badgeClass = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25';
                                @endphp
                                <span class="badge {{ $badgeClass }} fs-6 rounded-pill px-4 py-2 fw-bolder shadow-sm">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                        </div>

                        <div class="row row-cols-1 row-cols-sm-2 g-4 mb-5">
                            <div class="col">
                                <div class="p-3 bg-light rounded-3 border border-light h-100">
                                    <p class="mb-1 text-muted small fw-bold text-uppercase d-flex align-items-center"><i class='bx bx-calendar me-1'></i> Tanggal Pesan</p>
                                    <p class="fw-bold mb-0 text-dark fs-5">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 bg-light rounded-3 border border-light h-100">
                                    <p class="mb-1 text-muted small fw-bold text-uppercase d-flex align-items-center"><i class='bx bx-calendar-check me-1'></i> Perkiraan Selesai</p>
                                    <p class="fw-bold mb-0 text-dark fs-5">{{ $order->expected_completion_date ? \Carbon\Carbon::parse($order->expected_completion_date)->format('d M Y') : '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-4 d-flex align-items-center"><i class='bx bx-git-commit text-brand-600 me-2 fs-4'></i> Timeline Produksi</h5>
                        <div class="d-flex flex-column gap-3 mb-5">
                            @foreach($productionStages as $stage)
                                @php
                                    $tracking = $order->trackings->where('production_stage_id', $stage->id)->first();
                                    $statusIcon = 'bx-time-five text-muted opacity-50';
                                    $statusText = 'Belum Dimulai';
                                    $statusColor = 'text-muted';
                                    $rowBg = 'bg-light border border-light';
                                    $iconBg = 'bg-white text-muted';
                                    $badgeClass = 'bg-secondary text-secondary bg-opacity-10';
                                    $statusTextBadge = 'Menunggu';
                                    
                                    if ($tracking) {
                                        if ($tracking->completed_at) {
                                            $statusIcon = 'bx-check text-white';
                                            $statusText = 'Selesai pada ' . \Carbon\Carbon::parse($tracking->completed_at)->format('d M Y');
                                            $statusColor = 'text-success fw-bold';
                                            $iconBg = 'bg-success shadow-sm text-white';
                                            $rowBg = 'bg-success bg-opacity-10 border border-success border-opacity-25';
                                            $badgeClass = 'bg-success text-white shadow-sm';
                                            $statusTextBadge = 'Selesai';
                                        } elseif ($tracking->started_at) {
                                            $statusIcon = 'bx-loader-circle bx-spin text-white';
                                            $statusText = 'Sedang dikerjakan sejak ' . \Carbon\Carbon::parse($tracking->started_at)->format('d M Y');
                                            $statusColor = 'text-warning text-dark fw-bold';
                                            $iconBg = 'bg-warning shadow-sm text-dark';
                                            $rowBg = 'bg-warning bg-opacity-10 border border-warning border-opacity-25';
                                            $badgeClass = 'bg-warning text-dark shadow-sm';
                                            $statusTextBadge = 'Sedang Dikerjakan';
                                        }
                                    }
                                @endphp
                                <div class="d-flex flex-column flex-sm-row align-items-sm-center p-3 rounded-4 {{ $rowBg }} transition hover-shadow">
                                    <div class="d-flex align-items-center mb-2 mb-sm-0 flex-grow-1 min-w-0">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle {{ $iconBg }} me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                                            <i class='bx {{ $statusIcon }} fs-4'></i>
                                        </div>
                                        <div class="min-w-0 pe-2">
                                            <h6 class="fw-bolder text-dark mb-1 text-truncate">{{ $stage->stage_name }}</h6>
                                            <div class="small {{ $statusColor }} text-wrap">{{ $statusText }}</div>
                                        </div>
                                    </div>
                                    <div class="ms-sm-3 mt-2 mt-sm-0 align-self-start align-self-sm-center flex-shrink-0">
                                        <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2 fs-6 fw-bold d-inline-flex align-items-center">
                                            @if($statusTextBadge == 'Selesai') <i class='bx bx-check-circle me-1'></i> 
                                            @elseif($statusTextBadge == 'Sedang Dikerjakan') <i class='bx bx-cog bx-spin me-1'></i>
                                            @else <i class='bx bx-time me-1'></i>
                                            @endif
                                            {{ $statusTextBadge }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <h5 class="fw-bold mb-3">Item Pesanan</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pakaian</th>
                                        <th>Bahan</th>
                                        <th class="text-center">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td class="fw-bold">{{ $item->product_type }}</td>
                                        <td>{{ $item->fabric_details ?? '-' }}</td>
                                        <td class="text-center fw-bold text-primary">{{ $item->quantity }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <h5 class="fw-bolder mb-3 d-flex align-items-center"><i class='bx bx-wallet text-brand-600 me-2 fs-4'></i> Rincian Keuangan</h5>
                        <div class="bg-light p-4 rounded-4 shadow-sm border border-light">
                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <span class="text-muted fw-bold">Total Tagihan</span>
                                <span class="fw-bolder fs-5 text-dark">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <span class="text-muted fw-bold">Telah Dibayar</span>
                                <span class="fw-bolder fs-5 text-success">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-top border-2 border-secondary border-opacity-10 my-3"></div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bolder text-dark">Sisa Pembayaran</span>
                                <span class="fw-bolder fs-4 {{ $sisaTagihan > 0 ? 'text-danger' : 'text-success' }}">
                                    Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endif

        <style>
            .hover-shadow:hover {
                box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            }
            .transition {
                transition: all .3s ease;
            }
            .input-group-lg > .form-control, .input-group-lg > .input-group-text {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
            .form-control:focus {
                border-color: #8B5CF6;
                box-shadow: none;
            }
            .input-group:focus-within {
                box-shadow: 0 0 0 0.25rem rgba(139, 92, 246, 0.25) !important;
            }
            .tracking-wider {
                letter-spacing: .05em;
            }
        </style>
    </div>
</x-guest>
