<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row">
        @if($role == 'Superadmin')
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

        @elseif($role == 'Admin')
            <!-- Admin Cards -->
            <div class="col-xxl-3 col-md-6 mb-4">
                <div class="card info-card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Pesanan <span>| All Time</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width: 64px; height: 64px; font-size: 32px;">
                                <i class="bx bx-cart"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="mb-0 fs-3 fw-bold">{{ $totalPesanan }}</h6>
                                <span class="text-muted small pt-2 ps-1">Order Keseluruhan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6 mb-4">
                <div class="card info-card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Pesanan Baru <span>| Pending</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info" style="width: 64px; height: 64px; font-size: 32px;">
                                <i class="bx bx-bell"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="mb-0 fs-3 fw-bold">{{ $pesananBaru }}</h6>
                                <span class="text-muted small pt-2 ps-1">Order Masuk</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6 mb-4">
                <div class="card info-card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Komplain Aktif <span>| Revisi</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger" style="width: 64px; height: 64px; font-size: 32px;">
                                <i class="bx bx-error"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="mb-0 fs-3 fw-bold">{{ $komplainAktif }}</h6>
                                <span class="text-muted small pt-2 ps-1">Belum Selesai</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6 mb-4">
                <div class="card info-card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Stok Kain <span>| Tersedia</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success" style="width: 64px; height: 64px; font-size: 32px;">
                                <i class="bx bx-layer"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="mb-0 fs-3 fw-bold">{{ number_format($stokKain, 1, ',', '.') }}</h6>
                                <span class="text-muted small pt-2 ps-1">Meter</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- Pegawai Cards -->
            <div class="col-md-4 mb-4">
                <div class="card info-card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Tugas Saya <span>| Keseluruhan</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width: 64px; height: 64px; font-size: 32px;">
                                <i class="bx bx-task"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="mb-0 fs-3 fw-bold">{{ $tugasSaya }}</h6>
                                <span class="text-muted small pt-2 ps-1">Total Tugas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card info-card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Selesai <span>| Completed</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success" style="width: 64px; height: 64px; font-size: 32px;">
                                <i class="bx bx-check-circle"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="mb-0 fs-3 fw-bold">{{ $tugasSelesai }}</h6>
                                <span class="text-muted small pt-2 ps-1">Pekerjaan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card info-card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Belum Selesai <span>| On Progress</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning" style="width: 64px; height: 64px; font-size: 32px;">
                                <i class="bx bx-time"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="mb-0 fs-3 fw-bold">{{ $tugasBelumSelesai }}</h6>
                                <span class="text-muted small pt-2 ps-1">Harus dikerjakan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Grafik {{ $chartName }} <span>| 7 Hari Terakhir</span></h5>
                    <div id="dynamicChart"></div>
                </div>
            </div>
        </div>
    </div>

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
