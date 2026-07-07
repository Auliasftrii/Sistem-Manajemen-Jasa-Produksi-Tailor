<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row">
        <!-- Card 1 -->
        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card info-card sales-card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Pesanan <span>| All Time</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width: 64px; height: 64px; font-size: 32px;">
                            <i class="bx bx-cart"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="mb-0 fs-3 fw-bold">{{ $totalPesanan }}</h6>
                            <span class="text-muted small pt-2 ps-1">Pesanan Terdaftar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card info-card revenue-card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Pendapatan <span>| Bulan Ini</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success" style="width: 64px; height: 64px; font-size: 32px;">
                            <i class="bx bx-dollar-circle"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="mb-0 fs-4 fw-bold">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h6>
                            <span class="text-muted small pt-2 ps-1">Uang Masuk</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card info-card customers-card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Pelanggan <span>| All Time</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning" style="width: 64px; height: 64px; font-size: 32px;">
                            <i class="bx bx-user"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="mb-0 fs-3 fw-bold">{{ $pelanggan }}</h6>
                            <span class="text-muted small pt-2 ps-1">Orang</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card info-card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Belum Selesai <span>| On Progress</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger" style="width: 64px; height: 64px; font-size: 32px;">
                            <i class="bx bx-time"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="mb-0 fs-3 fw-bold">{{ $pesananBelumSelesai }}</h6>
                            <span class="text-muted small pt-2 ps-1">Pesanan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Grafik Pendapatan <span>| 7 Hari Terakhir</span></h5>
                    <div id="revenueChart"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                new ApexCharts(document.querySelector("#revenueChart"), {
                    series: [{
                        name: 'Pendapatan',
                        data: @json($chartData)
                    }],
                    chart: {
                        height: 350,
                        type: 'area',
                        toolbar: { show: false },
                    },
                    colors: ['#2eca6a'],
                    fill: {
                        type: "gradient",
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.3,
                            opacityTo: 0.4,
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
                                return "Rp " + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return "Rp " + val.toLocaleString('id-ID');
                            }
                        }
                    }
                }).render();
            });
        </script>
    @endpush

</x-app>
