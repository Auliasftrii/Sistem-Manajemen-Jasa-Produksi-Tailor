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
        @if($customer->measurements && count($customer->measurements) > 0)
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>Bagian</th>
                        <th>Ukuran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customer->measurements as $key => $val)
                        <tr>
                            <td>{{ $key }}</td>
                            <td>{{ $val }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-warning">
                Belum ada data ukuran untuk pelanggan ini.
            </div>
        @endif
    </div>
</div>
