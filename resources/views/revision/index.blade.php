<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">
        <div class="mb-3 text-end">
            <a href="{{ route('revision.create') }}" class="btn btn-primary"><i class='bx bx-plus'></i> Tambah Revisi</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Invoice</th>
                        <th scope="col">Pelanggan</th>
                        <th scope="col">Tanggal Komplain</th>
                        <th scope="col">Catatan Revisi</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revisions as $revision)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="fw-bold">{{ $revision->order->invoice_number }}</span></td>
                            <td>{{ $revision->order->customer->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($revision->reported_at)->format('d/m/Y H:i') }}</td>
                            <td>{{ Str::limit($revision->revision_notes, 50) }}</td>
                            <td>
                                @if($revision->status == 'Pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($revision->status == 'In Progress')
                                    <span class="badge bg-primary">In Progress</span>
                                @elseif($revision->status == 'Resolved')
                                    <span class="badge bg-success">Resolved</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <form action="{{ route('revision.update', $revision) }}" method="post" class="m-0">
                                        @csrf
                                        @method('put')
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <select name="status" class="form-select" onchange="this.form.submit()">
                                                <option value="Pending" @selected($revision->status == 'Pending')>Pending</option>
                                                <option value="In Progress" @selected($revision->status == 'In Progress')>In Progress</option>
                                                <option value="Resolved" @selected($revision->status == 'Resolved')>Resolved</option>
                                            </select>
                                        </div>
                                    </form>
                                    <form action="{{ route('revision.destroy', $revision) }}" method="post" class="m-0" onsubmit="return confirm('Yakin ingin menghapus revisi ini?')">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>
