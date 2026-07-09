<div class="row">
    <div class="col-md-6">
        <h5 class="fw-bold">Informasi Pelanggan</h5>
        <table class="table table-sm">
            <tr>
                <th width="30%">Nama</th>
                <td>: {{ $customer->name }}</td>
            </tr>
            <tr>
                <th>No. Telepon</th>
                <td>: {{ $customer->phone ?? '-' }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>: {{ $customer->address ?? '-' }}</td>
            </tr>
        </table>
    </div>
    
    <div class="col-md-6">
        <h5 class="fw-bold">Detail Ukuran (Measurements)</h5>
        @if($customer->measurements && $customer->measurements->count() > 0)
            @php
                $groupedMeasurements = $customer->measurements->groupBy(function($item) {
                    return $item->garmentCategory->name;
                });
            @endphp

            @foreach($groupedMeasurements as $categoryName => $measurements)
                <h6 class="mt-3 fw-bold text-primary">{{ $categoryName }}</h6>
                <table class="table table-bordered table-striped table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Bagian</th>
                            <th>Ukuran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($measurements as $m)
                            <tr>
                                <td>{{ $m->measurement_key }}</td>
                                <td>{{ $m->measurement_value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @else
            <div class="alert alert-warning mt-2">
                Belum ada data ukuran untuk pelanggan ini.
            </div>
        @endif
    </div>
</div>
