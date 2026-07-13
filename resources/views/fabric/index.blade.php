<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">
        <div class="mb-3 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class='bx bx-plus'></i> Tambah Kain</button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Kain</th>
                        <th scope="col">Jenis Kain</th>
                        <th scope="col">Warna</th>
                        <th scope="col">Stok Tersedia (Meter)</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fabrics as $fabric)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="fw-bold">{{ $fabric->name }}</span></td>
                            <td>{{ $fabric->fabric_type }}</td>
                            <td>{{ $fabric->color }}</td>
                            <td class="text-end fw-bold">{{ number_format($fabric->stocks_sum_quantity_in_meters ?? 0, 2, ',', '.') }}</td>
                            <td>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#restockModal{{ $fabric->id }}" title="Tambah Stok"><i class="bx bx-plus-circle"></i> Stok</button>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $fabric->id }}"><i class="bx bx-edit"></i></button>
                                <form action="{{ route('fabric.destroy', $fabric) }}" method="post" class="d-inline-block ms-1" onsubmit="return confirm('Yakin ingin menghapus data kain ini?')">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($fabrics as $fabric)
        <!-- Restock Modal -->
        <div class="modal fade" id="restockModal{{ $fabric->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('fabric.addStock', $fabric) }}" method="post" class="modal-content">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Tambah Stok Kain: {{ $fabric->name }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Stok Masuk (Meter) *</label>
                            <input type="number" step="0.1" name="quantity_in_meters" class="form-control" required placeholder="Contoh: 50.5">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan Stok</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal{{ $fabric->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('fabric.update', $fabric) }}" method="post" class="modal-content">
                    @csrf
                    @method('put')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kain</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Kain *</label>
                            <input type="text" name="name" class="form-control" value="{{ $fabric->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Kain *</label>
                            <input type="text" name="fabric_type" class="form-control" value="{{ $fabric->fabric_type }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Warna *</label>
                            <input type="text" name="color" class="form-control" value="{{ $fabric->color }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Stok (Meter) *</label>
                            <input type="number" step="0.1" name="quantity_in_meters" class="form-control" value="{{ $fabric->stocks_sum_quantity_in_meters ?? 0 }}" required min="0">
                            <small class="text-muted">Ubah angka ini untuk menyesuaikan total stok.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('fabric.store') }}" method="post" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Kain</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kain *</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Katun Toyobo Premium">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis Kain *</label>
                        <input type="text" name="fabric_type" class="form-control" required placeholder="Contoh: Katun">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Warna *</label>
                        <input type="text" name="color" class="form-control" required placeholder="Contoh: Putih Tulang">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Stok Awal (Meter) *</label>
                        <input type="number" step="0.1" name="quantity_in_meters" class="form-control" required min="0" placeholder="Contoh: 100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Kain</button>
                </div>
            </form>
        </div>
    </div>
</x-app>
