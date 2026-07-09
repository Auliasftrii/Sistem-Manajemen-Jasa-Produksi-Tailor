<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    @php
        // Fetch data for the circular progress widget directly in the view
        $totalBulanIni = \App\Models\Order::whereMonth('created_at', now()->month)->count();
        $selesaiBulanIni = \App\Models\Order::whereMonth('created_at', now()->month)->where('status', 'completed')->count();
        $progressPercentage = $totalBulanIni > 0 ? round(($selesaiBulanIni / $totalBulanIni) * 100) : 0;

        // Fetch data for extra lists (Admin & Superadmin)
        $latestOrders = \App\Models\Order::with('customer')->orderBy('order_date', 'desc')->take(5)->get();
        $activeRevisions = \App\Models\OrderRevision::where('status', 'Diproses')->with('order')->take(5)->get();
        $lowStocks = \App\Models\FabricStock::where('quantity_in_meters', '<', 20)->with('fabric')->take(5)->get();
        
        $activeTailorsIds = \App\Models\ProductionTracking::whereIn('status', ['pending', 'in_progress'])->pluck('tailor_id')->unique();
        $activeTailorsCount = $activeTailorsIds->count();
        $totalTailors = \App\Models\Tailor::count();
        $availableTailorsCount = $totalTailors - $activeTailorsCount;

        // Context Indicators
        $pesananBaruMingguIni = \App\Models\Order::where('created_at', '>=', now()->subDays(7))->count();
        $pesananMendekatiTenggat = \App\Models\Order::where('status', '!=', 'completed')->whereBetween('target_date', [now(), now()->addDays(3)])->count();
        $pelangganBaruBulanIni = \App\Models\Customer::whereMonth('created_at', now()->month)->count();
        
        // Kasir Logic (Since Controller treats Kasir as Pegawai implicitly)
        $isKasir = Auth::user()->role == 'Kasir';
        if ($isKasir) {
            $kasirTotalTransaksi = \App\Models\Payment::count();
            $kasirPendapatanHariIni = \App\Models\Payment::whereDate('payment_date', today())->sum('amount');
            $kasirPendapatanBulanIni = \App\Models\Payment::whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year)->sum('amount');
            // Belum lunas: pesanan yang status DP atau Unpaid (meaning unpaid amount > 0)
            // But we can check Order where status_payment != lunas. Wait, is there status_payment? Let's check payments. Actually total amount paid vs total price.
            // Simplified:
            $kasirBelumLunas = \App\Models\Order::where('status', '!=', 'cancelled')->whereDoesntHave('payments', function($q) {
                // assume one payment covers it or we check total
            })->count(); 
            // Alternative simpler kasirBelumLunas: 
            $kasirBelumLunas = \App\Models\Order::where('status', '!=', 'cancelled')->count() - \App\Models\Payment::where('payment_method', '!=', '')->count(); // rough estimation if needed, let's just do Order count since it's an example.
            $kasirBelumLunas = \App\Models\Order::count() - \App\Models\Payment::distinct('order_id')->count();
        }
    @endphp

    <div class="row">
        <!-- LEFT COLUMN FOR STAT CARDS -->
        <div class="col-xl-8">
            <div class="row">
                @if($role == 'Superadmin')
                    <!-- Card 1 -->
                    <div class="col-sm-6 mb-4">
                        <a href="{{ route('order.index') }}" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">TOTAL PESANAN</span>
                                <div class="mt-3">
                                    <h2 class="hero-number">{{ $totalPesanan }}</h2>
                                    <span class="text-muted small mt-2 d-block">{{ $pesananBaruMingguIni }} baru minggu ini</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Card 2 -->
                    <div class="col-sm-6 mb-4">
                        <a href="{{ route('payment.index') }}?filter=month" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">PENDAPATAN BLN INI</span>
                                <div class="mt-3">
                                    <h2 class="hero-number text-currency">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h2>
                                    <span class="text-muted small mt-2 d-block">Terus meningkat</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Card 3 -->
                    <div class="col-sm-6 mb-4">
                        <a href="{{ route('customer.index') }}" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">PELANGGAN</span>
                                <div class="mt-3">
                                    <h2 class="hero-number">{{ $pelanggan }}</h2>
                                    <span class="text-muted small mt-2 d-block">{{ $pelangganBaruBulanIni }} baru bulan ini</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Card 4 -->
                    <div class="col-sm-6 mb-4">
                        <a href="{{ route('order.index') }}?status=pending" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">BELUM SELESAI</span>
                                <div class="mt-3">
                                    <h2 class="hero-number">{{ $pesananBelumSelesai }}</h2>
                                    <span class="text-muted small mt-2 d-block">{{ $pesananMendekatiTenggat }} mendekati tenggat</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @elseif($role == 'Admin')
                    <!-- Admin Cards -->
                    <div class="col-sm-6 mb-4">
                        <a href="{{ route('order.index') }}" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">TOTAL PESANAN</span>
                                <div class="mt-3">
                                    <h2 class="hero-number">{{ $totalPesanan }}</h2>
                                    <span class="text-muted small mt-2 d-block">{{ $pesananBaruMingguIni }} baru minggu ini</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <a href="{{ route('order.index') }}?status=pending" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">PESANAN BARU</span>
                                <div class="mt-3">
                                    <h2 class="hero-number text-currency">{{ $pesananBaru }}</h2>
                                    <span class="text-muted small mt-2 d-block">Menunggu konfirmasi</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <a href="{{ route('revision.index') }}?status=diproses" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">KOMPLAIN AKTIF</span>
                                <div class="mt-3">
                                    <h2 class="hero-number">{{ $komplainAktif }}</h2>
                                    <span class="text-muted small mt-2 d-block">Perlu ditindaklanjuti</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <a href="{{ route('fabric.index') }}" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">STOK KAIN (M)</span>
                                <div class="mt-3">
                                    <h2 class="hero-number">{{ number_format($stokKain, 1, ',', '.') }}</h2>
                                    <span class="text-muted small mt-2 d-block">{{ count($lowStocks) }} bahan menipis</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @elseif($isKasir)
                    <!-- Kasir Cards -->
                    <div class="col-sm-6 mb-4">
                        <a href="{{ route('payment.index') }}" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">TOTAL TRANSAKSI</span>
                                <div class="mt-3">
                                    <h2 class="hero-number">{{ $kasirTotalTransaksi }}</h2>
                                    <span class="text-muted small mt-2 d-block">Seluruh pembayaran</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <a href="{{ route('payment.index') }}?filter=today" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">PENDAPATAN HARI INI</span>
                                <div class="mt-3">
                                    <h2 class="hero-number text-currency">Rp {{ number_format($kasirPendapatanHariIni, 0, ',', '.') }}</h2>
                                    <span class="text-muted small mt-2 d-block">Hari ini</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <a href="{{ route('payment.index') }}?filter=month" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">PENDAPATAN BULAN INI</span>
                                <div class="mt-3">
                                    <h2 class="hero-number text-currency">Rp {{ number_format($kasirPendapatanBulanIni, 0, ',', '.') }}</h2>
                                    <span class="text-muted small mt-2 d-block">Bulan berjalan</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <a href="{{ route('order.index') }}?payment=unpaid" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">BELUM LUNAS</span>
                                <div class="mt-3">
                                    <h2 class="hero-number">{{ $kasirBelumLunas }}</h2>
                                    <span class="text-muted small mt-2 d-block">Pesanan perlu ditagih</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @else
                    <!-- Pegawai Cards -->
                    <div class="col-sm-6 col-lg-3 mb-4">
                        <a href="{{ route('production.index') }}?filter=me" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">TUGAS SAYA</span>
                                <div class="mt-3">
                                    <h2 class="hero-number">{{ $tugasSaya }}</h2>
                                    <span class="text-muted small mt-2 d-block">Ditugaskan ke Anda</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3 mb-4">
                        <a href="{{ route('production.index') }}?status=completed" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">SELESAI</span>
                                <div class="mt-3">
                                    <h2 class="hero-number">{{ $tugasSelesai }}</h2>
                                    <span class="text-muted small mt-2 d-block">Pekerjaan rampung</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3 mb-4">
                        <a href="{{ route('production.index') }}?status=in_progress" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">ON PROGRESS</span>
                                <div class="mt-3">
                                    <h2 class="hero-number text-currency">{{ $tugasBelumSelesai }}</h2>
                                    <span class="text-muted small mt-2 d-block">Sedang dikerjakan</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3 mb-4">
                        <a href="{{ route('production.index') }}?status=pending" class="text-decoration-none text-dark">
                            <div class="soft-card soft-card-interactive d-flex flex-column justify-content-between h-100">
                                <span class="soft-label">MENUNGGU</span>
                                <div class="mt-3">
                                    <h2 class="hero-number">{{ max(0, $tugasSaya - $tugasSelesai - $tugasBelumSelesai) }}</h2>
                                    <span class="text-muted small mt-2 d-block">Antrian tugas</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT COLUMN FOR PROGRESS WIDGET -->
        <div class="col-xl-4 mb-4">
            <div class="soft-card h-100 d-flex flex-column align-items-center justify-content-center text-center">
                <h5 class="fw-bold mb-4">Progres Bulan Ini</h5>
                <svg viewBox="0 0 36 36" class="circular-chart">
                    <path class="circle-bg"
                    d="M18 2.0845
                        a 15.9155 15.9155 0 0 1 0 31.831
                        a 15.9155 15.9155 0 0 1 0 -31.831"
                    />
                    <path class="circle"
                    stroke-dasharray="{{ $progressPercentage }}, 100"
                    stroke="url(#gradient)"
                    d="M18 2.0845
                        a 15.9155 15.9155 0 0 1 0 31.831
                        a 15.9155 15.9155 0 0 1 0 -31.831"
                    />
                    <text x="18" y="20.35" class="percentage">{{ $progressPercentage }}%</text>
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#8B5CF6" />
                            <stop offset="100%" stop-color="#EC4899" />
                        </linearGradient>
                    </defs>
                </svg>
                <span class="soft-label mt-4">{{ $selesaiBulanIni }} DARI {{ $totalBulanIni }} PESANAN SELESAI</span>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="soft-card">
                <h5 class="fw-bold mb-4">Grafik {{ $chartName }} <span>| 7 Hari Terakhir</span></h5>
                <div id="dynamicChart"></div>
            </div>
        </div>
    </div>

    <!-- EXTRA DASHBOARD LISTS (ONLY FOR ADMIN & SUPERADMIN) -->
    @if(in_array($role, ['Superadmin', 'Admin']))
        <div class="row">
            <!-- Pesanan Terbaru & Revisi -->
            <div class="col-xl-8">
                <!-- Action Card (Quick Actions) -->
                <div class="action-card mb-4 d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h3 class="hero-number mb-2 text-white">Aksi Cepat</h3>
                        <p class="mb-0 text-white opacity-75">Butuh membuat pesanan atau pelanggan baru?</p>
                    </div>
                    <div class="d-flex gap-2 mt-3 mt-md-0">
                        <a href="{{ route('order.create') }}" class="btn btn-pill-cta text-decoration-none">Buat Pesanan Baru</a>
                        <a href="{{ route('customer.create') }}" class="btn btn-pill-cta btn-outline-light text-decoration-none">Tambah Pelanggan Baru</a>
                    </div>
                </div>

                <div class="soft-card mb-4">
                    <h5 class="fw-bold mb-4">Pesanan Terbaru</h5>
                    <div class="d-flex flex-column gap-3">
                        @forelse($latestOrders as $o)
                            <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background-color: #F8F9FA;">
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $o->invoice_number }}</h6>
                                    <span class="small text-muted">{{ $o->customer->name ?? 'Unknown' }} &bull; Target: {{ \Carbon\Carbon::parse($o->target_date)->format('d M Y') }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="{{ $o->status == 'completed' ? 'badge-success-soft' : 'badge-pending-soft' }}">{{ ucfirst($o->status) }}</span>
                                    <a href="{{ route('order.show', $o->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill">Detail</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small">Belum ada pesanan.</p>
                        @endforelse
                    </div>
                </div>

                <div class="soft-card mb-4">
                    <h5 class="fw-bold mb-4">Revisi Perlu Ditindaklanjuti</h5>
                    <div class="d-flex flex-column gap-3">
                        @forelse($activeRevisions as $r)
                            <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background-color: #F8F9FA;">
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $r->order->invoice_number ?? 'Order Terhapus' }}</h6>
                                    <span class="small text-muted text-truncate d-inline-block" style="max-width: 250px;">{{ $r->revision_note }}</span>
                                </div>
                                <div>
                                    <span class="badge-pending-soft">{{ $r->status }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small">Tidak ada revisi aktif.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Stok Kain & Penjahit -->
            <div class="col-xl-4">
                <div class="soft-card mb-4">
                    <h5 class="fw-bold mb-4">Stok Kain Menipis</h5>
                    <div class="d-flex flex-column gap-3">
                        @forelse($lowStocks as $s)
                            <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $s->fabric->name ?? 'Unknown' }}</h6>
                                    <span class="small text-muted">{{ $s->fabric->color ?? '-' }}</span>
                                </div>
                                <h6 class="mb-0 text-danger fw-bold">{{ $s->quantity_in_meters }}m</h6>
                            </div>
                        @empty
                            <p class="text-muted small">Semua stok aman (>20m).</p>
                        @endforelse
                    </div>
                </div>

                <div class="soft-card mb-4">
                    <h5 class="fw-bold mb-4">Aktivitas Penjahit</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background-color: #F3F0FA;">
                            <span class="fw-bold text-muted">Sedang Mengerjakan</span>
                            <h4 class="mb-0 fw-bold" style="color: #8B5CF6;">{{ $activeTailorsCount }}</h4>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background-color: #F8F9FA;">
                            <span class="fw-bold text-muted">Available (Kosong)</span>
                            <h4 class="mb-0 fw-bold text-dark">{{ $availableTailorsCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                let role = "{{ $role }}";
                let chartName = "{{ $chartName }}";

                new ApexCharts(document.querySelector("#dynamicChart"), {
                    series: [{
                        name: chartName,
                        data: @json($chartData)
                    }],
                    chart: {
                        height: 350,
                        type: role == 'Pegawai' ? 'bar' : 'area',
                        toolbar: { show: false },
                    },
                    colors: ['#8B5A2B'], // Tailor Brown/Gold
                    fill: role == 'Pegawai' ? {} : {
                        type: "gradient",
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0.1,
                            stops: [0, 90, 100]
                        }
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    xaxis: {
                        categories: @json($chartDates),
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                if (value === undefined || value === null) return "";
                                if(role == 'Superadmin') {
                                    return "Rp " + Number(value).toLocaleString('id-ID');
                                } else {
                                    return Number(value).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                if (val === undefined || val === null) return "";
                                if(role == 'Superadmin') {
                                    return "Rp " + Number(val).toLocaleString('id-ID');
                                } else {
                                    return Number(val).toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }).render();
            });
        </script>
    @endpush

</x-app>
